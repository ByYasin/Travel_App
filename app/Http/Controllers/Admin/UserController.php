<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Kullanıcı listesini getir (filtreleme ve sayfalama ile)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = User::with('role');
            
            
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            
            if ($role = $request->input('role')) {
                if ($role == 'admin') {
                    $query->where('role_id', Role::ADMIN);
                } else if ($role == 'user') {
                    $query->where('role_id', Role::USER);
                }
            }
            
            
            $orderBy = $request->input('order_by', 'created_at');
            $direction = $request->input('direction', 'desc');
            
            
            $allowedOrderFields = ['name', 'email', 'created_at'];
            if (!in_array($orderBy, $allowedOrderFields)) {
                $orderBy = 'created_at';
            }
            
            $query->orderBy($orderBy, $direction);
            
            
            $users = $query->paginate($request->input('per_page', 10));
            
           
            $users->getCollection()->transform(function ($user) {
                $roles = [];
                if ($user->role) {
                    if ($user->role_id == Role::ADMIN) {
                        $roles[] = 'admin';
                    } else {
                        $roles[] = 'user';
                    }
                }
                
                $user->roles = $roles;
                $user->is_active = (bool)$user->is_active;
                return $user;
            });
            
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kullanıcı listesi alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kullanıcı detaylarını getir
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kullanıcı bulunamadı',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'roles' => 'array',
                'is_active' => 'boolean'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            

            $role_id = Role::USER; 
            if ($request->has('roles') && is_array($request->roles)) {
                if (in_array('admin', $request->roles)) {
                    $role_id = Role::ADMIN;
                }
            }
            
           
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role_id,
                'is_active' => $request->filled('is_active') ? $request->is_active : true
            ]);
            
            
            $user->roles = $request->has('roles') && is_array($request->roles) ? $request->roles : ['user'];
            
            return response()->json([
                'message' => 'Kullanıcı başarıyla oluşturuldu',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kullanıcı oluşturulurken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
                'roles' => 'array',
                'is_active' => 'boolean',
                'gender' => 'nullable|string|in:male,female,other',
                'birthdate' => 'nullable|date',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->is_active = $request->is_active;
            

            if ($request->has('gender')) {
                $user->gender = $request->gender;
            }
            
            if ($request->has('birthdate')) {
                $user->birthdate = $request->birthdate;
            }
            
            if ($request->has('phone')) {
                $user->phone = $request->phone;
            }
            
            if ($request->has('address')) {
                $user->address = $request->address;
            }
            
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
           
            if ($request->has('roles') && is_array($request->roles)) {
                if (in_array('admin', $request->roles)) {
                    $user->role_id = Role::ADMIN;
                } else {
                    $user->role_id = Role::USER;
                }
            }
            
            $user->save();
            
            return response()->json([
                'message' => 'Kullanıcı başarıyla güncellendi',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kullanıcı güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            

            if (auth()->check() && $user->id === auth()->id()) {
                return response()->json([
                    'message' => 'Kendi hesabınızı silemezsiniz'
                ], 403);
            }
            

            if ($user->role_id === Role::ADMIN) {
                $adminCount = User::where('role_id', Role::ADMIN)->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'message' => 'Son admin kullanıcısını silemezsiniz'
                    ], 403);
                }
            }
            
            $user->delete();
            
            return response()->json([
                'message' => 'Kullanıcı başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kullanıcı silinirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**

     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles()
    {
        try {
            $roles = Role::all();
            return response()->json($roles);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Roller alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
