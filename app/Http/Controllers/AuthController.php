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
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
 
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = \Validator::make($request->all(),[
           'MSSV' => 'required|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:8'
        ]);
        if($validator->fails())return response()->json($validator->errors());
        $user = User::create([
           'MSSV' => $request->MSSV,
           'email' => $request->email,
           'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(
        ['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
      }
 
 
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        if (!Auth::attempt($request->only('MSSV', 'password')))  {
           return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('MSSV', $request['MSSV'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Chào '.$user->name.'! Chúc an lành',
           'access_token' => $token, 'token_type' => 'Bearer',]);
    }
    public function logout()  {
        auth()->user()->tokens()->delete();
        return ['message' => 'Bạn đã thoát ứng dụng và token đã xóa'];
    }    
 
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function me()
    // {
    //     return response()->json(auth()->user());
    // }
 
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
    // public function refresh()
    // {
    //     return $this->respondWithToken(auth()->refresh());
    // }
 
    // /**
    //  * Get the token array structure.
    //  *
    //  * @param  string $token
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         // 'expires_in' => auth()->factory()->getTTL() * 60
    //         'expires_in' => auth('api')->factory()->getTTL() * 60
    //     ]);
    // }
}