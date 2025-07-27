<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\ClearanceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::with(['user', 'assignedTo', 'attachments']);

        // Filter based on user role
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type_ar', 'like', '%' . $request->type . '%');
        }

        if ($request->filled('municipality')) {
            $query->where('municipality_ar', 'like', '%' . $request->municipality . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', '%' . $search . '%')
                  ->orWhere('type_ar', 'like', '%' . $search . '%')
                  ->orWhere('description_ar', 'like', '%' . $search . '%');
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transactionTypes = [
            'رخصة بناء' => 'Building Permit',
            'رخصة هدم' => 'Demolition Permit',
            'رخصة تجارية' => 'Commercial License',
            'شهادة إتمام بناء' => 'Building Completion Certificate',
            'رخصة مزاولة مهنة' => 'Professional Practice License',
            'تصريح عمل' => 'Work Permit',
            'رخصة استثمار' => 'Investment License',
            'شهادة منشأ' => 'Certificate of Origin',
        ];

        $municipalities = [
            'أمانة منطقة الرياض',
            'أمانة المنطقة الشرقية',
            'أمانة منطقة مكة المكرمة',
            'أمانة منطقة المدينة المنورة',
            'أمانة منطقة القصيم',
            'أمانة منطقة عسير',
            'أمانة منطقة تبوك',
            'أمانة منطقة حائل',
            'أمانة منطقة الحدود الشمالية',
            'أمانة منطقة جازان',
            'أمانة منطقة نجران',
            'أمانة منطقة الباحة',
            'أمانة منطقة الجوف',
        ];

        return view('transactions.create', compact('transactionTypes', 'municipalities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_ar' => 'required|string|max:255',
            'municipality_ar' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ], [
            'type_ar.required' => 'نوع المعاملة مطلوب',
            'municipality_ar.required' => 'البلدية مطلوبة',
            'description_ar.required' => 'وصف المعاملة مطلوب',
            'priority.required' => 'الأولوية مطلوبة',
            'attachments.*.mimes' => 'نوع الملف غير مدعوم. الأنواع المدعومة: PDF, DOC, DOCX, JPG, JPEG, PNG',
            'attachments.*.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
        ]);

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_number' => Transaction::generateTransactionNumber(),
                'type_ar' => $request->type_ar,
                'type_en' => $request->type_en ?? '',
                'status' => 'pending',
                'municipality_ar' => $request->municipality_ar,
                'municipality_en' => $request->municipality_en ?? '',
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en ?? '',
                'notes' => $request->notes,
                'priority' => $request->priority,
                'submission_date' => now(),
                'expected_completion_date' => now()->addDays(30), // Default 30 days
                'last_updated_by_user_at' => now(),
            ]);

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $this->uploadAttachment($transaction, $file, $request->input('attachment_descriptions', []));
                }
            }

            // Create notification for admins
            $this->notifyAdminsNewTransaction($transaction);

            // Create notification for user
            Notification::sendToUser(Auth::id(), [
                'type' => 'transaction_update',
                'title_ar' => 'تم إنشاء المعاملة بنجاح',
                'title_en' => 'Transaction Created Successfully',
                'message_ar' => "تم إنشاء المعاملة رقم {$transaction->transaction_number} بنجاح وهي قيد المراجعة",
                'message_en' => "Transaction {$transaction->transaction_number} has been created successfully and is under review",
                'icon' => 'fa-check-circle',
                'color' => 'success',
                'priority' => 'normal',
                'channel' => 'database',
                'action_url' => route('transactions.show', $transaction),
                'action_text_ar' => 'عرض المعاملة',
                'action_text_en' => 'View Transaction',
                'notifiable_type' => Transaction::class,
                'notifiable_id' => $transaction->id,
            ]);

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                           ->with('success', 'تم إنشاء المعاملة بنجاح. رقم المعاملة: ' . $transaction->transaction_number);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المعاملة. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // Temporarily disabled permission check
        // if (!Auth::user()->hasRole('admin') && $transaction->user_id !== Auth::id()) {
        //     abort(403, 'غير مصرح لك بعرض هذه المعاملة');
        // }

        $transaction->load(['user', 'assignedTo', 'attachments.uploadedBy', 'notifications' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        // Only allow user to edit their own pending transactions
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'pending') {
            abort(403, 'لا يمكن تعديل هذه المعاملة');
        }

        $transactionTypes = [
            'رخصة بناء' => 'Building Permit',
            'رخصة هدم' => 'Demolition Permit',
            'رخصة تجارية' => 'Commercial License',
            'شهادة إتمام بناء' => 'Building Completion Certificate',
            'رخصة مزاولة مهنة' => 'Professional Practice License',
            'تصريح عمل' => 'Work Permit',
            'رخصة استثمار' => 'Investment License',
            'شهادة منشأ' => 'Certificate of Origin',
        ];

        $municipalities = [
            'أمانة منطقة الرياض',
            'أمانة المنطقة الشرقية',
            'أمانة منطقة مكة المكرمة',
            'أمانة منطقة المدينة المنورة',
            'أمانة منطقة القصيم',
            'أمانة منطقة عسير',
            'أمانة منطقة تبوك',
            'أمانة منطقة حائل',
            'أمانة منطقة الحدود الشمالية',
            'أمانة منطقة جازان',
            'أمانة منطقة نجران',
            'أمانة منطقة الباحة',
            'أمانة منطقة الجوف',
        ];

        return view('transactions.edit', compact('transaction', 'transactionTypes', 'municipalities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Only allow user to update their own pending transactions
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'pending') {
            abort(403, 'لا يمكن تعديل هذه المعاملة');
        }

        $request->validate([
            'type_ar' => 'required|string|max:255',
            'municipality_ar' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $transaction->update([
                'type_ar' => $request->type_ar,
                'type_en' => $request->type_en ?? '',
                'municipality_ar' => $request->municipality_ar,
                'municipality_en' => $request->municipality_en ?? '',
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en ?? '',
                'notes' => $request->notes,
                'priority' => $request->priority,
                'last_updated_by_user_at' => now(),
            ]);

            // Handle new file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $this->uploadAttachment($transaction, $file, $request->input('attachment_descriptions', []));
                }
            }

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                           ->with('success', 'تم تحديث المعاملة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المعاملة');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Only allow user to delete their own pending transactions
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'pending') {
            abort(403, 'لا يمكن حذف هذه المعاملة');
        }

        DB::beginTransaction();
        try {
            // Delete attachments
            foreach ($transaction->attachments as $attachment) {
                if (Storage::exists($attachment->file_path)) {
                    Storage::delete($attachment->file_path);
                }
                $attachment->delete();
            }

            // Delete notifications
            $transaction->notifications()->delete();

            // Delete transaction
            $transaction->delete();

            DB::commit();

            return redirect()->route('transactions.index')
                           ->with('success', 'تم حذف المعاملة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء حذف المعاملة');
        }
    }

    /**
     * Update transaction status or create clearance request
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        // Temporarily disabled role check
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403, 'غير مصرح لك بتحديث حالة المعاملة');
        // }

        $request->validate([
            'status' => 'required|in:pending,under_review,completed,clearance_requested,cancelled',
            'admin_notes' => 'nullable|string',
            'clearance_feedback' => 'required_if:status,clearance_requested|string',
        ], [
            'status.required' => 'حالة المعاملة مطلوبة',
            'clearance_feedback.required_if' => 'ملاحظات طلب التخليص مطلوبة',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $transaction->status;
            
            // Handle clearance request
            if ($request->status === 'clearance_requested') {
                // Create clearance request
                $clearanceRequest = ClearanceRequest::create([
                    'transaction_id' => $transaction->id,
                    'requested_by' => Auth::id(),
                    'feedback_ar' => $request->clearance_feedback,
                    'status' => 'pending',
                ]);

                // Update transaction status
                $transaction->update([
                    'status' => 'clearance_requested',
                    'admin_notes' => $request->admin_notes,
                    'assigned_to' => Auth::id(),
                    'last_updated_by_admin_at' => now(),
                ]);

                // Send notification to user
                $clearanceRequest->sendNotificationToUser();

                DB::commit();
                return back()->with('success', 'تم إرسال طلب التخليص للمستخدم بنجاح');
            } else {
                // Handle other status updates
                $transaction->update([
                    'status' => $request->status,
                    'admin_notes' => $request->admin_notes,
                    'assigned_to' => Auth::id(),
                    'last_updated_by_admin_at' => now(),
                    'actual_completion_date' => $request->status === 'completed' ? now() : null,
                ]);

                // Create status update notification
                $statusMessages = [
                    'under_review' => 'المعاملة قيد المراجعة',
                    'completed' => 'تم إنجاز المعاملة بنجاح',
                    'cancelled' => 'تم إلغاء المعاملة',
                ];

                Notification::sendToUser($transaction->user_id, [
                    'type' => 'transaction_update',
                    'title_ar' => 'تحديث حالة المعاملة',
                    'title_en' => 'Transaction Status Updated',
                    'message_ar' => "تم تحديث حالة المعاملة رقم {$transaction->transaction_number} إلى: " . ($statusMessages[$request->status] ?? $request->status),
                    'message_en' => "Transaction {$transaction->transaction_number} status updated to: {$request->status}",
                    'icon' => $request->status === 'completed' ? 'fa-check-circle' : 'fa-info-circle',
                    'color' => $request->status === 'completed' ? 'success' : 'info',
                    'priority' => 'normal',
                    'channel' => 'database',
                    'action_url' => route('transactions.show', $transaction),
                    'action_text_ar' => 'عرض المعاملة',
                    'action_text_en' => 'View Transaction',
                    'notifiable_type' => Transaction::class,
                    'notifiable_id' => $transaction->id,
                    'data' => [
                        'old_status' => $oldStatus,
                        'new_status' => $request->status,
                        'admin_notes' => $request->admin_notes,
                    ],
                ]);

                DB::commit();
                return back()->with('success', 'تم تحديث حالة المعاملة بنجاح');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء تحديث حالة المعاملة: ' . $e->getMessage());
        }
    }

    /**
     * Upload attachment for transaction
     */
    public function uploadAttachment(Transaction $transaction, $file, $descriptions = [])
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        
        // Generate unique filename
        $filename = Str::uuid() . '.' . $extension;
        $path = 'transactions/' . $transaction->id . '/' . $filename;
        
        // Store file
        $file->storeAs('transactions/' . $transaction->id, $filename);
        
        // Determine category
        $category = 'document';
        if (str_starts_with($mimeType, 'image/')) {
            $category = 'image';
        }
        
        // Create attachment record
        return Attachment::create([
            'attachable_type' => Transaction::class,
            'attachable_id' => $transaction->id,
            'uploaded_by' => Auth::id(),
            'original_name' => $originalName,
            'file_name' => $filename,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'file_size' => $size,
            'category' => $category,
            'description_ar' => $descriptions[0] ?? '',
            'is_public' => false,
            'is_verified' => false,
        ]);
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment(Attachment $attachment)
    {
        // Check permissions
        if (!Auth::user()->hasRole('admin') && $attachment->uploaded_by !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذا الملف');
        }

        // Check if transaction is still editable
        if ($attachment->attachable->status !== 'pending' && !Auth::user()->hasRole('admin')) {
            abort(403, 'لا يمكن حذف الملفات من معاملة غير قابلة للتعديل');
        }

        try {
            // Delete file from storage
            if (Storage::exists($attachment->file_path)) {
                Storage::delete($attachment->file_path);
            }

            // Delete record
            $attachment->delete();

            return back()->with('success', 'تم حذف الملف بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف الملف');
        }
    }

    /**
     * Search transactions (AJAX)
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::with(['user', 'assignedTo']);

        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', '%' . $search . '%')
                  ->orWhere('type_ar', 'like', '%' . $search . '%')
                  ->orWhere('description_ar', 'like', '%' . $search . '%');
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->limit(10)->get();

        return response()->json($transactions);
    }

    /**
     * Notify admins about new transaction
     */
    private function notifyAdminsNewTransaction(Transaction $transaction)
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            Notification::sendToUser($admin->id, [
                'type' => 'transaction_update',
                'title_ar' => 'معاملة جديدة تحتاج مراجعة',
                'title_en' => 'New Transaction Needs Review',
                'message_ar' => "معاملة جديدة رقم {$transaction->transaction_number} من {$transaction->user->display_name} تحتاج إلى مراجعة",
                'message_en' => "New transaction {$transaction->transaction_number} from {$transaction->user->display_name} needs review",
                'icon' => 'fa-file-alt',
                'color' => 'warning',
                'priority' => 'normal',
                'channel' => 'database',
                'action_url' => route('transactions.show', $transaction),
                'action_text_ar' => 'مراجعة المعاملة',
                'action_text_en' => 'Review Transaction',
                'notifiable_type' => Transaction::class,
                'notifiable_id' => $transaction->id,
            ]);
        }
    }
}
