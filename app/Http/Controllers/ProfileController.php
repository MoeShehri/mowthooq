<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile page.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validation rules based on user type
        $rules = [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'national_id' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'address_ar' => 'nullable|string|max:500',
            'city_ar' => 'nullable|string|max:100',
        ];

        // Add conditional validation based on user type
        if ($request->user_type === 'office' || $request->user_type === 'developer') {
            $rules['company_name_ar'] = 'required|string|max:255';
            $rules['commercial_register'] = 'required|string|max:50';
        }

        $validatedData = $request->validate($rules, [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.unique' => 'رقم الهوية مستخدم من قبل',
            'company_name_ar.required' => 'اسم الشركة مطلوب',
            'commercial_register.required' => 'السجل التجاري مطلوب',
        ]);

        // Update user data
        $user->update($validatedData);

        return redirect()->route('profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'الصورة الشخصية مطلوبة',
            'avatar.image' => 'يجب أن يكون الملف صورة',
            'avatar.mimes' => 'نوع الصورة غير مدعوم',
            'avatar.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        // Store new avatar
        $avatarName = time() . '_' . $user->id . '.' . $request->avatar->extension();
        $request->avatar->storeAs('public/avatars', $avatarName);

        // Update user avatar
        $user->update(['avatar' => $avatarName]);

        return redirect()->route('profile.show')->with('success', 'تم تحديث الصورة الشخصية بنجاح');
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
            Storage::delete('public/avatars/' . $user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('profile.show')->with('success', 'تم حذف الصورة الشخصية بنجاح');
    }

    /**
     * Show account settings.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update account settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'language' => 'required|in:ar,en',
            'timezone' => 'required|string',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // Update user settings (you might want to create a separate settings table)
        $user->update([
            'language' => $validatedData['language'],
            'timezone' => $validatedData['timezone'],
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
        ]);

        return redirect()->route('profile.settings')->with('success', 'تم تحديث إعدادات الحساب بنجاح');
    }

    /**
     * Complete profile setup for new users.
     */
    public function completeProfile()
    {
        $user = Auth::user();
        
        // Check if profile is already complete
        if ($this->isProfileComplete($user)) {
            return redirect()->route('dashboard');
        }

        return view('profile.complete', compact('user'));
    }

    /**
     * Store completed profile data.
     */
    public function storeCompleteProfile(Request $request)
    {
        $user = Auth::user();

        // Simplified validation - only essential fields
        $rules = [
            'phone' => 'required|string|max:20',
            'address_ar' => 'nullable|string|max:500',
            'city_ar' => 'nullable|string|max:100',
        ];

        // Only require company info if user type is office or developer
        if ($user->user_type === 'office' || $user->user_type === 'developer') {
            $rules['company_name_ar'] = 'required|string|max:255';
            $rules['commercial_register'] = 'required|string|max:50';
        }

        $validatedData = $request->validate($rules, [
            'phone.required' => 'رقم الهاتف مطلوب',
            'company_name_ar.required' => 'اسم الشركة مطلوب',
            'commercial_register.required' => 'السجل التجاري مطلوب',
        ]);

        // Update user data
        $user->update($validatedData);

        // Mark profile as complete
        $user->update(['profile_completed' => true]);

        return redirect()->route('dashboard')->with('success', 'تم إكمال الملف الشخصي بنجاح');
    }

    /**
     * Check if user profile is complete.
     */
    private function isProfileComplete($user)
    {
        $required = ['first_name', 'last_name', 'email', 'phone'];
        
        // Add company-specific requirements
        if ($user->user_type === 'office' || $user->user_type === 'developer') {
            $required = array_merge($required, ['company_name_ar', 'commercial_register']);
        }

        foreach ($required as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }

        return true;
    }
}

