<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Kullanıcı kaydı
     */
    public function register(Request $request)
    {
        Log::info('Register isteği geldi', ['data' => $request->all()]);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // User rolü ID'si 1'dir
        $userRoleId = 1; // Varsayılan user rolü (sabit değer)
        
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $userRoleId
            ]);
            
            // SPA için bir token oluştur - bu artık frontend tarafında saklanmayacak
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('Register başarılı', ['user_id' => $user->id, 'role_id' => $userRoleId]);
            
            // Kullanıcıyı otomatik olarak giriş yap
            Auth::login($user);
            
            return response()->json([
                'user' => $user,
                'token' => $token // Frontend için hala token dönebiliriz, ama oturum zaten kurulmuş olacak
            ]);
        } catch (\Exception $e) {
            Log::error('Register başarısız', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Kullanıcı girişi
     */
    public function login(Request $request)
    {
        Log::info('Login isteği geldi', ['data' => $request->all()]);
        
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean']
        ]);
        
        // Giriş denemesi - Auth facade kullanarak
        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            Log::warning('Login başarısız', ['email' => $request->email]);
            
            // 401 hatası döndür - Giriş başarısız
            return response()->json([
                'message' => 'E-posta veya şifre hatalı. Lütfen bilgilerinizi kontrol ediniz.'
            ], 401);
        }

        // Kullanıcı veritabanında var mı kontrol et
        $user = User::where('email', $request->email)->first();
        
        // Kullanıcı bulunamadıysa (veritabanı temizlemesi vb. sebeplerle) 
        if (!$user) {
            Log::warning('Login başarısız: Kullanıcı veritabanında yok', ['email' => $request->email]);
            return response()->json([
                'message' => 'Kullanıcı hesabınız bulunamadı. Lütfen yönetici ile iletişime geçin.'
            ], 401);
        }
        
        // Eski tokenları temizle
        $user->tokens()->delete();
        
        // Yeni token oluştur
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('Login başarılı', ['user_id' => $user->id]);
        
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Kullanıcı çıkışı
     */
    public function logout(Request $request)
    {
        Log::info('Logout isteği geldi', ['user_id' => $request->user()->id]);
        
        // Kullanıcının tüm tokenlarını sil
        $request->user()->tokens()->delete();
        
        // Oturumu sonlandır
        Auth::guard('web')->logout();
        
        // CSRF token'ı yenile
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Başarıyla çıkış yapıldı.'
        ]);
    }

    /**
     * Kullanıcı bilgilerini döndür
     */
    public function user(Request $request)
    {
        Log::info('User profil bilgileri istendi', ['user_id' => $request->user()->id]);
        
        return response()->json($request->user());
    }
    
    /**
     * Kullanıcı profil bilgilerini günceller
     */
    public function updateProfile(Request $request)
    {
        Log::info('Profile güncelleme isteği geldi', ['user_id' => $request->user()->id, 'data' => $request->all()]);
        
        $user = $request->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gender' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Temel bilgileri güncelle
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        
        // Cinsiyet bilgisini güncelle
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        
        // Doğum tarihi bilgisini güncelle
        if ($request->has('birthdate')) {
            try {
                $user->birthdate = $request->birthdate;
            } catch (\Exception $e) {
                Log::error('Doğum tarihi dönüşüm hatası', ['error' => $e->getMessage(), 'birthdate' => $request->birthdate]);
                // Hata durumunda null atama
                $user->birthdate = null;
            }
        }
        
        $user->save();
        
        Log::info('Profil güncellendi', ['user_id' => $user->id]);
        
        return response()->json($user);
    }
    
    /**
     * Kullanıcı şifresini günceller
     */
    public function updatePassword(Request $request)
    {
        Log::info('Şifre güncelleme isteği geldi', ['user_id' => $request->user()->id]);
        
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);
        
        $user = $request->user();
        
        // Mevcut şifreyi kontrol et
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Şifre güncelleme başarısız: Mevcut şifre hatalı', ['user_id' => $user->id]);
            
            throw ValidationException::withMessages([
                'current_password' => ['Mevcut şifre hatalı.'],
            ]);
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        Log::info('Şifre güncellendi', ['user_id' => $user->id]);
        
        return response()->json([
            'message' => 'Şifreniz başarıyla güncellendi.'
        ]);
    }
}
