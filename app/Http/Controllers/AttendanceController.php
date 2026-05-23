<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * عرض واجهة السكّانر (للأدمين والكوتش)
     */
    public function scanner()
    {
        return view('admin_space.attendance.scanner');
    }

    /**
     * معالجة الكود لي تسكانى
     */
    public function markAttendance(Request $request)
    {
        $token = $request->user_id; // النص لي جاي من السكّانر

        // 1. فك الشفرة (ID-TIMESTAMP)
        $parts = explode('-', $token);
        if (count($parts) !== 2) {
            return response()->json(['success' => false, 'message' => 'FORMAT DE CODE INVALIDE']);
        }

        $userId = $parts[0];
        $tokenTimestamp = $parts[1];

        // 2. التحقق من وقت الكود (واش فات 15 ثانية مثلاً باش نعطيو شوية د الوقت)
        $currentTimestamp = Math_floor(time() / 10);
        if (abs($currentTimestamp - $tokenTimestamp) > 2) {
            return response()->json(['success' => false, 'message' => 'CODE EXPIRED! RE-SCANNEZ']);
        }

        // 3. البحث عن المنخرط
        $user = User::find($userId);
        if (!$user || $user->role !== 'client') {
            return response()->json(['success' => false, 'message' => 'MEMBRE INTROUVABLE']);
        }

        // 4. التحقق من أن الاشتراك مازال خدام (Abonnement Valide)
        if ($user->expired_at && Carbon::now()->gt($user->expired_at)) {
            return response()->json(['success' => false, 'message' => 'ACCÈS REFUSÉ : ABONNEMENT EXPIRÉ']);
        }

        // 5. تسجيل الحضور (إلا ما كاينش حضور اليوم مثلاً)
        $alreadyChecked = Attendance::where('user_id', $user->id)
                            ->whereDate('check_in', Carbon::today())
                            ->exists();

        if ($alreadyChecked) {
            return response()->json(['success' => true, 'member_name' => $user->name, 'message' => 'DÉJÀ POINTÉ AUJOURD\'HUI']);
        }

        Attendance::create([
            'user_id' => $user->id,
            'check_in' => now(),
        ]);

        return response()->json([
            'success' => true, 
            'member_name' => $user->name, 
            'message' => 'POINTAGE RÉUSSI - BIENVENUE'
        ]);
    }
}

// دالة مساعدة لتقريب الوقت
function Math_floor($number) {
    return (int) floor($number);
}