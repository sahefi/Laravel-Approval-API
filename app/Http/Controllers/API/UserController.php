<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    function updateValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:users,id'],
            'username'=> ['string','required',Rule::unique('users','username')->ignore($data['id']??null)],
            'email'=>['email','required',Rule::unique('users','email')->ignore($data['id']??null)],
            'id_role'=>['string','required','exists:roles,id']
        ]);
        return $dataValidator;
    }

    function deleteValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:users,id'],

        ]);

        return $dataValidator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->filled('per_page') ? $request->input('per_page'):10;

        $data = Users::select('users.id','users.username','roles.name as roles_name')
                ->leftJoin('roles','users.id_role','=','roles.id')
                ->orderBy('users.username','asc')
                ->paginate($per_page);


        return new JsonResponse([
            "status" =>true,
            "message"=>'Success',
            "data"=>$data
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->query('id');
        $data = Users::select('users.*','roles.name as roles_name')
                ->leftJoin('roles','users.id_role','=','roles.id')
                ->where('users.id',$id)
                ->first();

        if($data){
            return new JsonResponse([
                'status'=>true,
                'message'=>'Success',
                'data'=> [
                    "id"=>$data->id,
                    "username"=>$data->username,
                    "email"=>$data->email,
                    'role'=>[
                        "id_role"=>$data->id_role,
                        "roles_name"=>$data->roles_name
                    ]
                ]
            ]);
        }else{
            return new JsonResponse([
                'status'=>true,
                'message'=>'Data Not Exist',
                'data'=>null
            ]);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = $this->updateValidator($request->all());
        if($validator->fails()){
            return new JsonResponse(['error' => $validator->errors()],422);
        }

        $user = Users::find($request->input('id'));

        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->id_role = $request->input('id_role');

        $user->save();
        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>[
                'id'=>$user->id,
                'username'=>$user->username,
                'email'=>$user->email,
                'role'=>[
                    'id_role'=>$user->role->id??null,
                    'name_role'=>$user->role->name??null
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = $this->deleteValidator($request->all());
        if($validator->fails()){
            return new JsonResponse(['error' => $validator->errors()],422);
        }

        $user = Users::find($request->query('id'));
        $user->delete();

        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>null
        ]);
    }
}
