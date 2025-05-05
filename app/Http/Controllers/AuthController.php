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

     */
    public function register(Request $request)
    {
        Log::info('Register isteği geldi', ['data' => $request->all()]);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        
        $userRoleId = 1; 
        
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $userRoleId
            ]);
            
            
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('Register başarılı', ['user_id' => $user->id, 'role_id' => $userRoleId]);
            
            
            Auth::login($user);
            
            return response()->json([
                'user' => $user,
                'token' => $token 
            ]);
        } catch (\Exception $e) {
            Log::error('Register başarısız', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    /**

     */
    public function login(Request $request)
    {
        Log::info('Login isteği geldi', ['data' => $request->all()]);
        
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean']
        ]);
        
   
        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            Log::warning('Login başarısız', ['email' => $request->email]);
            
            
            return response()->json([
                'message' => 'E-posta veya şifre hatalı. Lütfen bilgilerinizi kontrol ediniz.'
            ], 401);
        }

        
        $user = User::where('email', $request->email)->first();
        
        
        if (!$user) {
            Log::warning('Login başarısız: Kullanıcı veritabanında yok', ['email' => $request->email]);
            return response()->json([
                'message' => 'Kullanıcı hesabınız bulunamadı. Lütfen yönetici ile iletişime geçin.'
            ], 401);
        }
        
        
        $user->tokens()->delete();
        
        
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('Login başarılı', ['user_id' => $user->id]);
        
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
    
     */
    public function logout(Request $request)
    {
        Log::info('Logout isteği geldi', ['user_id' => $request->user()->id]);
        
        
        $request->user()->tokens()->delete();
        
        
        Auth::guard('web')->logout();
        
       
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Başarıyla çıkış yapıldı.'
        ]);
    }

    /**

     */
    public function user(Request $request)
    {
        Log::info('User profil bilgileri istendi', ['user_id' => $request->user()->id]);
        
        return response()->json($request->user());
    }
    
    /**
     
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
        
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        
        
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        
        
        if ($request->has('birthdate')) {
            try {
                $user->birthdate = $request->birthdate;
            } catch (\Exception $e) {
                Log::error('Doğum tarihi dönüşüm hatası', ['error' => $e->getMessage(), 'birthdate' => $request->birthdate]);
                
                $user->birthdate = null;
            }
        }
        
        $user->save();
        
        Log::info('Profil güncellendi', ['user_id' => $user->id]);
        
        return response()->json($user);
    }
    
    /**
     
     */
    public function updatePassword(Request $request)
    {
        Log::info('Şifre güncelleme isteği geldi', ['user_id' => $request->user()->id]);
        
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);
        
        $user = $request->user();
        
        
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
