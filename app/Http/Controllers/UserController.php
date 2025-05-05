<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Giriş yapmış kullanıcının profilini göster
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = Auth::user();
        
      
        Log::info('Profil bilgileri görüntüleniyor', [
            'user_id' => $user->id,
            'has_gender' => !is_null($user->gender),
            'has_birthdate' => !is_null($user->birthdate),
        ]);
        
        return response()->json($user);
    }

    /**
     * Kullanıcı profilini güncelle
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            
            DB::flushQueryLog();
            DB::enableQueryLog();
            

            $user = Auth::user();

            
            Log::debug('Profil güncelleme ham veriler:', [
                'user_id' => $user->id, 
                'request_all' => $request->all(),
                'has_gender' => $request->has('gender'),
                'gender' => $request->input('gender'),
                'gender_type' => gettype($request->input('gender')),
                'has_birthdate' => $request->has('birthdate'),
                'birthdate' => $request->input('birthdate'),
                'birthdate_type' => gettype($request->input('birthdate')),
            ]);

            
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'gender' => 'required|string|in:male,female,other',
                'birthdate' => 'nullable|date',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:1000',
            ]);
            
            
            Log::debug('Doğrulanmış veriler:', $validatedData);
            
 
            try {
              
                $user->name = $validatedData['name'];
                $user->email = $validatedData['email'];
                $user->phone = $validatedData['phone'] ?? $user->phone;
                $user->address = $validatedData['address'] ?? $user->address;
                
                
                $user->save();
                
                
                $updateFields = [];
                
                
                if ($request->has('gender')) {
                    $gender = $request->input('gender');
                    $updateFields['gender'] = $gender === '' ? null : $gender;
                }
                
                
                if ($request->has('birthdate')) {
                    $birthdate = $request->input('birthdate');
                    $updateFields['birthdate'] = $birthdate === '' ? null : $birthdate;
                }
                
                
                if (!empty($updateFields)) {
                    Log::debug('Direkt SQL ile güncellenecek alanlar:', $updateFields);
                    
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update($updateFields);
                    
                    
                    Log::debug('Çalıştırılan SQL sorguları:', DB::getQueryLog());
                }
                
                
                $updatedUser = User::find($user->id)->fresh();
                
               
                Log::info('Güncellenmiş kullanıcı verileri:', [
                    'user_id' => $updatedUser->id,
                    'name' => $updatedUser->name,
                    'email' => $updatedUser->email,
                    'gender' => $updatedUser->gender,
                    'gender_type' => gettype($updatedUser->gender),
                    'birthdate' => $updatedUser->birthdate,
                    'birthdate_type' => gettype($updatedUser->birthdate),
                    'phone' => $updatedUser->phone,
                    'address' => $updatedUser->address
                ]);
                
                
                return response()->json($updatedUser);
            } catch (\Exception $e) {
                Log::error('Profil güncelleme işlem hatası:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Profil güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Profil güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**

     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validatedData = $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|different:current_password|confirmed',
            ]);

            $user->password = bcrypt($validatedData['new_password']);
            $user->save();

            Log::info('Şifre başarıyla güncellendi', [
                'user_id' => $user->id
            ]);

            return response()->json(['message' => 'Şifre başarıyla güncellendi.']);
            
        } catch (\Exception $e) {
            Log::error('Şifre güncelleme hatası:', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'message' => 'Şifre güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
} 