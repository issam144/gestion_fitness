<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
// المكتبات اللازمة للإشعارات
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserPendingNotification;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * المسار الذي يتم التوجيه إليه بعد التسجيل (سيتم تجاوزه في دالة registered)
     */
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * التثبت من صحة البيانات المدخلة
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * إنشاء المستخدم الجديد وإرسال إشعار للأدمين
     */
    protected function create(array $data)
    {
        // 1. إنشاء المستخدم بحالة "غير مفعل"
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'client', // قمت بتغييرها لـ client لضمان ظهورها في الفلتر، أو اتركها null كما كانت
            'is_approved' => false, 
        ]);

        // 2. جلب كافة المسؤولين (Admins) لإعلامهم
        $admins = User::where('role', 'admin')->get();

        // 3. إرسال الإشعار (تأكد من إنشاء ملف NewUserPendingNotification أولاً)
        if ($admins->count() > 0) {
            Notification::send($admins, new NewUserPendingNotification($user));
        }

        return $user;
    }

    /**
     * منطق ما بعد التسجيل الناجح
     */
    protected function registered(Request $request, $user)
    {
        // تسجيل الخروج فوراً لأن حسابه يحتاج تفعيل
        auth()->logout();

        // التوجيه لصفحة "في انتظار الموافقة"
        return redirect()->route('preinscription.reussie');
    }
}