<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Coach; // تأكد من وجود الموديل

class ProfileController extends Controller
{
    // عرض صفحة تغيير كلمة المرور الإجباري
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    // تحديث كلمة المرور الإجباري
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = 0;
        $user->save();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Mot de passe mis à jour !');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Mot de passe mis à jour !');
        } else {
            return redirect()->route('client.dashboard')->with('success', 'Mot de passe mis à jour !');
        }
    }

    // الدالة الموحدة لتحديث البروفايل (Admin, Coach, Client)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // 2. تحديث بيانات جدول Users
        $user->name = $request->name;
        $user->email = $request->email;
        
        // تحديث الهاتف في جدول users (إذا كان الحقل موجوداً)
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        // 3. رفع الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            // حفظ الجديدة
            $path = $request->file('image')->store('profiles', 'public');
            $user->image = $path;
        }

        // 4. تحديث كلمة المرور
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 5. تحديث بيانات الكوتش الإضافية (إلا كان المستخدم كوتش)
        if ($user->role === 'coach') {
            $coach = Coach::where('user_id', $user->id)->first();
            if ($coach) {
                $coach->update([
                    'specialite' => $request->specialite ?? $coach->specialite,
                    'telephone' => $request->telephone ?? $request->phone ?? $coach->telephone,
                    'image' => $user->image // مزامنة الصورة مع جدول الكوتش
                ]);
            }
        }

        return back()->with('success', 'PROFIL MIS À JOUR AVEC SUCCÈS');
    }
}