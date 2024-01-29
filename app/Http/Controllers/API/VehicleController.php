<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
