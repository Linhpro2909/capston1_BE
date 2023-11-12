<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','profilelist','update']]);
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
           'MSSV' => 'required|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:8'

        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
      }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        // if (!Auth::attempt($request->only('MSSV', 'password')))  {
        //    return response()->json(['message' => 'Unauthorized'], 401);
        // }
        // $user = User::where('MSSV', $request['MSSV'])->firstOrFail();
        // $token = $user->createToken('auth_token')->plainTextToken;
        // return response()->json(['message' => 'Chào '.$user->name.'! Chúc an lành',
        //    'access_token' => $token, 'token_type' => 'Bearer',]);
        $validator = Validator::make($request->all(), [
            'MSSV' => 'required',
            // 'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }  
    public function profilelist()  {
        $user = User::get();
        if(!$user){
            return ['message' => 'Thất bại'];
        }
        
        return response()->json(['data' => $user, 'message' => 'Thành công'], 200);
    }
    public function update(Request $request)
    {
        $user= User::where('id', $request->id)->first();
        // $user = User::findOrFail($request->id);
            if (!$user) {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'tài khoản không tồn tại',
                ]);
            } else {
                $user->update($request->all());
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Đã cập nhật tài khoản thành công!',
                ]);
            }
    }

   


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function userProfile() {
        return response()->json(auth('api')->user());
    }
 


    // /**
    //  * Log the user out (Invalidate the token).
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */


    // /**
    //  * Refresh a token.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */

    
 
    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
    }
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }
}
