<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{

    function createValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'name'=>['string','required',Rule::unique('roles','name')->whereNull('deleted_at'),],
        ]);

        return $dataValidator;
    }

    function updateValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:roles,id'],
            'name'=>['string','required',Rule::unique('roles','name')->ignore($data['id']??null)->whereNull('deleted_at')],
        ]);

        return $dataValidator;
    }

    function deleteValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:roles,id'],

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

        $data = Roles::whereNull('deleted_at')->orderBy('name','asc')->paginate($per_page);


        return new JsonResponse([
            "status" =>true,
            "message"=>'Success',
            "data"=>$data
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->createValidator($request->all());
        if($validator->fails()){
            return new JsonResponse(['error' => $validator->errors()],422);
        }

        $role = new Roles();

        $role->name=$request->input('name');

        $role->save();

        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>[
                'id'=>$role->id,
                'name'=>$role->name,
                "created_at"=>$role->created_at,
                "updated_at"=>$role->update_at,
                "deleted_at"=>$role->deleted_at
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $role = Roles::find($request->input('id'));

        $role->name=$request->input('name');
        $role->save();

        return new JsonResponse([
            "status"=>true,
            "message"=>'Success',
            'data'=>$role
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
        $roles = Roles::find($request->query('id'));
        if($roles->deleted_at !== null){
            return new JsonResponse([
                "status"=>true,
                "message"=>'ID Already Deleted',
                'data'=>null
            ]);
        }else {
            $roles->deleted_at = Carbon::now();
            $roles->save();

            return new JsonResponse([
                "status"=>true,
                "message"=>'Success',
                'data'=>null
            ]);
        }
    }
}
