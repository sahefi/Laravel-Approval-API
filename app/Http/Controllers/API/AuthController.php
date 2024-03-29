<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{


    function registerValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'username'=> ['string','required',Rule::unique('users','username')],
            'email'=>['email','required',Rule::unique('users','email')],
            'password'=>['string','required'],
            'id_role'=>['string','required','exists:roles,id']
        ]);
        return $dataValidator;
    }

    function loginValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'username'=> ['string','required'],
            'password'=>['string','required']
        ]);
        return $dataValidator;
    }
 /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function register(Request $request)
    {
        $validator = $this->registerValidator($request->all());
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],400);
        }

        $users = new Users();

        $users->username = $request->input('username');
        $users->email = $request->input('email');
        $users->password = Hash::make($request->input('password'));
        $users->id_role = $request->input('id_role');

        $users->save();
        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>[
                'id'=>$users->id,
                'username'=>$users->username,
                'email'=>$users->email,
                'role'=>[
                    'name'=>$users->role->name?? null
                ]
            ]
        ]);
    }

    public function login(Request $request)
    {
        $validator = $this->loginValidator($request->all());
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],400);
        }
        $credentials = $request->only(['username', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return new JsonResponse([
                'status'=>false,
                'error'=>'Credential Not Match'
            ] , 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=> true,
            'message'=>'Success',
            'data'=>[
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 600000
            ]
        ]);
    }


}
