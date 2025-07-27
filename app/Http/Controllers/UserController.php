<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Temporarily disabled permission middleware
        // $this->middleware('permission:view-users')->only(['index', 'show']);
        // $this->middleware('permission:create-users')->only(['create', 'store']);
        // $this->middleware('permission:edit-users')->only(['edit', 'update']);
        // $this->middleware('permission:delete-users')->only(['destroy']);
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with('roles');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->has('user_type') && !empty($request->user_type)) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        $userTypes = [
            'individual' => 'أفراد',
            'engineering_office' => 'مكاتب هندسية',
            'real_estate_developer' => 'مطورين عقاريين',
            'admin' => 'مدير'
        ];

        return view('users.create', compact('roles', 'userTypes'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:individual,engineering_office,real_estate_developer,admin',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name'
        ], [
            'name.required' => 'اسم المستخدم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'user_type.required' => 'نوع المستخدم مطلوب',
            'role.required' => 'الدور مطلوب'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
        ]);

        $user->assignRole($request->role);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'مرحباً بك في موثوق',
            'message' => 'تم إنشاء حسابك بنجاح. يمكنك الآن البدء في استخدام المنصة.',
            'type' => 'welcome',
            'color' => 'success',
            'icon' => 'fa-user-check'
        ]);

        return redirect()->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['roles', 'transactions', 'notifications']);
        
        $stats = [
            'total_transactions' => $user->transactions()->count(),
            'pending_transactions' => $user->transactions()->where('status', 'pending')->count(),
            'completed_transactions' => $user->transactions()->where('status', 'completed')->count(),
            'rejected_transactions' => $user->transactions()->where('status', 'rejected')->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userTypes = [
            'individual' => 'أفراد',
            'engineering_office' => 'مكاتب هندسية',
            'real_estate_developer' => 'مطورين عقاريين',
            'admin' => 'مدير'
        ];

        return view('users.edit', compact('user', 'roles', 'userTypes'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'user_type' => 'required|in:individual,engineering_office,real_estate_developer,admin',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم المستخدم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'user_type.required' => 'نوع المستخدم مطلوب',
            'role.required' => 'الدور مطلوب'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'is_active' => $request->has('is_active')
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        $user->syncRoles([$request->role]);

        // Create notification for user
        Notification::create([
            'user_id' => $user->id,
            'title' => 'تم تحديث بياناتك',
            'message' => 'تم تحديث بيانات حسابك بواسطة المدير.',
            'type' => 'account_update',
            'color' => 'info',
            'icon' => 'fa-user-edit'
        ]);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        // Check if user has transactions
        if ($user->transactions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف المستخدم لأنه يحتوي على معاملات');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        // Create notification for user
        Notification::create([
            'user_id' => $user->id,
            'title' => 'تغيير حالة الحساب',
            'message' => $status . ' حسابك بواسطة المدير.',
            'type' => 'account_status',
            'color' => $user->is_active ? 'success' : 'warning',
            'icon' => $user->is_active ? 'fa-user-check' : 'fa-user-times'
        ]);

        return redirect()->back()
            ->with('success', $status . ' المستخدم بنجاح');
    }

    /**
     * Send notification to user
     */
    public function sendNotification(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|string',
            'color' => 'required|in:primary,secondary,success,danger,warning,info,light,dark'
        ], [
            'title.required' => 'عنوان الإشعار مطلوب',
            'message.required' => 'نص الإشعار مطلوب',
            'type.required' => 'نوع الإشعار مطلوب',
            'color.required' => 'لون الإشعار مطلوب'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'color' => $request->color,
            'icon' => 'fa-bell',
            'sender_id' => auth()->id()
        ]);

        return redirect()->back()
            ->with('success', 'تم إرسال الإشعار بنجاح');
    }
}
