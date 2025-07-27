<?php

namespace App\Http\Controllers;

use App\Models\ClearanceRequest;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClearanceRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of clearance requests for the authenticated user.
     */
    public function index()
    {
        $clearanceRequests = ClearanceRequest::whereHas('transaction', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['transaction', 'requestedBy'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('clearance-requests.index', compact('clearanceRequests'));
    }

    /**
     * Show the user response form for a clearance request
     */
    public function showUserResponse(ClearanceRequest $clearanceRequest)
    {
        // Temporarily disabled permission check
        // if ($clearanceRequest->transaction->user_id !== Auth::id()) {
        //     abort(403, 'غير مصرح لك بالوصول لهذا الطلب');
        // }

        // Check if already responded
        if ($clearanceRequest->status !== 'pending') {
            return redirect()->route('transactions.show', $clearanceRequest->transaction)
                ->with('info', 'تم الرد على هذا الطلب مسبقاً');
        }

        return view('clearance-requests.user-response', compact('clearanceRequest'));
    }

    /**
     * Handle user response to clearance request
     */
    public function respond(Request $request, ClearanceRequest $clearanceRequest)
    {
        // Temporarily disabled permission check
        // if ($clearanceRequest->transaction->user_id !== Auth::id()) {
        //     abort(403, 'غير مصرح لك بالرد على هذا الطلب');
        // }

        // Check if already responded
        if ($clearanceRequest->status !== 'pending') {
            return redirect()->route('transactions.show', $clearanceRequest->transaction)
                ->with('error', 'تم الرد على هذا الطلب مسبقاً');
        }

        $request->validate([
            'response' => 'required|in:approved,rejected',
            'user_response' => 'nullable|string|max:1000',
        ], [
            'response.required' => 'يجب اختيار نوع الرد',
            'response.in' => 'نوع الرد غير صحيح',
        ]);

        DB::beginTransaction();
        try {
            // Update clearance request
            $clearanceRequest->update([
                'status' => $request->response,
                'user_response' => $request->user_response,
                'responded_at' => now(),
                'responded_by' => Auth::id(),
            ]);

            // Update transaction status based on response
            if ($request->response === 'approved') {
                $clearanceRequest->transaction->update([
                    'status' => 'under_review',
                    'last_updated_by_admin_at' => now(),
                ]);
                $message = 'تم قبول طلب التخليص. ستنتقل المعاملة إلى مرحلة المراجعة.';
            } else {
                $clearanceRequest->transaction->update([
                    'status' => 'pending',
                    'last_updated_by_admin_at' => now(),
                ]);
                $message = 'تم رفض طلب التخليص. ستعود المعاملة للمراجعة الإضافية.';
            }

            // Send notification to admin
            Notification::sendToUser($clearanceRequest->requested_by, [
                'type' => 'clearance_response',
                'title_ar' => 'رد على طلب التخليص',
                'title_en' => 'Clearance Request Response',
                'message_ar' => "تم الرد على طلب التخليص للمعاملة رقم {$clearanceRequest->transaction->transaction_number}",
                'message_en' => "Response received for clearance request of transaction {$clearanceRequest->transaction->transaction_number}",
                'icon' => $request->response === 'approved' ? 'fa-check-circle' : 'fa-times-circle',
                'color' => $request->response === 'approved' ? 'success' : 'warning',
                'priority' => 'high',
                'channel' => 'database',
                'notifiable_type' => 'App\\Models\\Transaction',
                'notifiable_id' => $clearanceRequest->transaction->id,
                'action_url' => route('transactions.show', $clearanceRequest->transaction),
                'action_text_ar' => 'عرض المعاملة',
                'action_text_en' => 'View Transaction',
                'data' => [
                    'clearance_request_id' => $clearanceRequest->id,
                    'response' => $request->response,
                    'user_response' => $request->user_response,
                ],
            ]);

            DB::commit();
            return redirect()->route('transactions.show', $clearanceRequest->transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء إرسال الرد: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ClearanceRequest $clearanceRequest)
    {
        // Check if user owns the transaction
        if ($clearanceRequest->transaction->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذا الطلب');
        }

        $clearanceRequest->load(['transaction', 'requestedBy', 'respondedBy']);

        return view('clearance-requests.show', compact('clearanceRequest'));
    }

    /**
     * Approve the clearance request.
     */
    public function approve(Request $request, ClearanceRequest $clearanceRequest)
    {
        // Check if user owns the transaction
        if ($clearanceRequest->transaction->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالموافقة على هذا الطلب');
        }

        // Check if already responded
        if ($clearanceRequest->status !== 'pending') {
            return back()->with('error', 'تم الرد على هذا الطلب مسبقاً');
        }

        $request->validate([
            'user_response' => 'nullable|string|max:1000',
        ], [
            'user_response.max' => 'الرد لا يجب أن يتجاوز 1000 حرف',
        ]);

        DB::beginTransaction();
        try {
            // Update clearance request
            $clearanceRequest->update([
                'status' => 'approved',
                'user_response' => $request->user_response,
                'responded_at' => now(),
                'responded_by' => Auth::id(),
            ]);

            // Update transaction status to under_review
            $clearanceRequest->transaction->update([
                'status' => 'under_review',
                'last_updated_by_user_at' => now(),
            ]);

            // Send notification to admin/staff
            Notification::sendToUser($clearanceRequest->requested_by, [
                'type' => 'clearance_approved',
                'title_ar' => 'تم الموافقة على طلب التخليص',
                'title_en' => 'Clearance Request Approved',
                'message_ar' => "تم الموافقة على طلب التخليص للمعاملة رقم {$clearanceRequest->transaction->transaction_number} من قبل المستخدم.",
                'message_en' => "Clearance request for transaction {$clearanceRequest->transaction->transaction_number} has been approved by the user.",
                'icon' => 'fa-check-circle',
                'color' => 'success',
                'priority' => 'normal',
                'channel' => 'database',
                'action_url' => route('transactions.show', $clearanceRequest->transaction),
                'action_text_ar' => 'عرض المعاملة',
                'action_text_en' => 'View Transaction',
                'notifiable_type' => ClearanceRequest::class,
                'notifiable_id' => $clearanceRequest->id,
                'data' => [
                    'clearance_request_id' => $clearanceRequest->id,
                    'transaction_id' => $clearanceRequest->transaction_id,
                    'user_response' => $request->user_response,
                ],
            ]);

            DB::commit();

            return redirect()->route('clearance-requests.show', $clearanceRequest)
                           ->with('success', 'تم الموافقة على طلب التخليص بنجاح. ستتم مراجعة المعاملة الآن.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء الموافقة على الطلب');
        }
    }

    /**
     * Reject the clearance request.
     */
    public function reject(Request $request, ClearanceRequest $clearanceRequest)
    {
        // Check if user owns the transaction
        if ($clearanceRequest->transaction->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك برفض هذا الطلب');
        }

        // Check if already responded
        if ($clearanceRequest->status !== 'pending') {
            return back()->with('error', 'تم الرد على هذا الطلب مسبقاً');
        }

        $request->validate([
            'user_response' => 'required|string|max:1000',
        ], [
            'user_response.required' => 'يرجى كتابة سبب الرفض',
            'user_response.max' => 'الرد لا يجب أن يتجاوز 1000 حرف',
        ]);

        DB::beginTransaction();
        try {
            // Update clearance request
            $clearanceRequest->update([
                'status' => 'rejected',
                'user_response' => $request->user_response,
                'responded_at' => now(),
                'responded_by' => Auth::id(),
            ]);

            // Keep transaction status as clearance_requested for now
            $clearanceRequest->transaction->update([
                'last_updated_by_user_at' => now(),
            ]);

            // Send notification to admin/staff
            Notification::sendToUser($clearanceRequest->requested_by, [
                'type' => 'clearance_rejected',
                'title_ar' => 'تم رفض طلب التخليص',
                'title_en' => 'Clearance Request Rejected',
                'message_ar' => "تم رفض طلب التخليص للمعاملة رقم {$clearanceRequest->transaction->transaction_number} من قبل المستخدم.",
                'message_en' => "Clearance request for transaction {$clearanceRequest->transaction->transaction_number} has been rejected by the user.",
                'icon' => 'fa-times-circle',
                'color' => 'danger',
                'priority' => 'high',
                'channel' => 'database',
                'action_url' => route('transactions.show', $clearanceRequest->transaction),
                'action_text_ar' => 'عرض المعاملة',
                'action_text_en' => 'View Transaction',
                'notifiable_type' => ClearanceRequest::class,
                'notifiable_id' => $clearanceRequest->id,
                'data' => [
                    'clearance_request_id' => $clearanceRequest->id,
                    'transaction_id' => $clearanceRequest->transaction_id,
                    'user_response' => $request->user_response,
                ],
            ]);

            DB::commit();

            return redirect()->route('clearance-requests.show', $clearanceRequest)
                           ->with('success', 'تم رفض طلب التخليص. سيتم إعادة النظر في المعاملة.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء رفض الطلب');
        }
    }

    /**
     * Display clearance requests for admin/staff.
     */
    public function adminIndex()
    {
        $clearanceRequests = ClearanceRequest::with(['transaction.user', 'requestedBy', 'respondedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('clearance-requests.admin-index', compact('clearanceRequests'));
    }
}

