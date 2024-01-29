<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    function createValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'name'=>['string','required',],
            'type'=>['required'],
            'fuel_consumption'=>['numeric','required'],
            'service_schedule'=>['date','required'],
        ]);

        return $dataValidator;
    }

    function updateValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:vehicles,id'],
            'name'=>['string','required',],
            'type'=>['required'],
            'fuel_consumption'=>['numeric','required'],
            'service_schedule'=>['date','required'],
        ]);

        return $dataValidator;
    }

    function deleteValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:vehicles,id'],

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

        $data = Vehicles::orderBy('name','asc')->paginate($per_page);

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

        $vehicle = new Vehicles();

        $vehicle->name=$request->input('name');
        $vehicle->type=$request->input('type');
        $vehicle->fuel_consumption=$request->input('fuel_consumption');
        $vehicle->service_schedule=$request->input('service_schedule');

        $vehicle->save();

        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>[
                'id'=>$vehicle->id,
                'name'=>$vehicle->name,
                "type"=>$vehicle->type,
                "fuel_consumption"=>$vehicle->fuel_consumption,
                "service_schedule"=>$vehicle->service_schedule
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

        $vehicle = Vehicles::find($request->input('id'));

        $vehicle->name=$request->input('name');
        $vehicle->type=$request->input('type');
        $vehicle->fuel_consumption=$request->input('fuel_consumption');
        $vehicle->service_schedule=$request->input('service_schedule');

        $vehicle->save();

        return new JsonResponse([
            "status"=>true,
            "message"=>'Success',
            'data'=>$vehicle
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

        $vehicle = Vehicles::find($request->query('id'));
        $vehicle->delete();

        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>null
        ]);
    }
}
