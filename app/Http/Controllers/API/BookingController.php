<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    function createValidator(array $data)
    {
        $dataValidator = Validator::make($data,[
            'driver'=>['string','required'],
            'id_vehicle'=>['string','required','exists:vehicles,id'],
            'applicant'=>['string','required'],
            'status'=>['required'],
            'id_approver'=>['string','required','exists:users,id'],
            'start_book'=>['date','required'],
            'end_book'=>['date','required']
        ]);

        return $dataValidator;
    }

    function approvalValidator(array $data){
        $dataValidator = Validator::make($data,[
            'id'=>['uuid','required','exists:bookings,id'],
            'status'=>['required']
        ]);
        return $dataValidator;
    }
    public function index()
    {
        //
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

        $inputStartDate = $request->input('start_book');
        $inputEndDate = $request->input('end_book');

        $startDate = Carbon::createFromFormat('d-m-Y', $inputStartDate)->toDateTimeString();
        $endDate = Carbon::createFromFormat('d-m-Y', $inputEndDate)->toDateTimeString();

        $booking = new Booking();

        $booking->driver=$request->input('driver');
        $booking->id_vehicle=$request->input('id_vehicle');
        $booking->applicant=$request->input('applicant');
        $booking->id_approver=$request->input('id_approver');
        $booking->start_book=$startDate;
        $booking->end_book=$endDate;

        $booking->save();

        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>[
                'id'=>$booking->id,
                'driver'=>$booking->driver,
                'applicant'=>$booking->applicant,
                'start_book'=>$booking->start_book,
                'end_book'=>$booking->end_book,
                'apporved_by'=>[
                    'id_approver'=>$booking->approver->id??null,
                    'name'=>$booking->approver->username??null
                ],
                'vehicle'=>[
                    'id_vehicle'=>$booking->vehicle->id??null,
                    'name'=>$booking->vehicle->name??null
                ]
            ]
        ]);

    }

    public function approve(Request $request)
    {
        $validator = $this->approvalValidator($request->all());
        if($validator->fails()){
            return new JsonResponse(['error' => $validator->errors()],422);
        }

        $booking = Booking::find($request->input('id'));

        $booking->status = $request->input('status');

        $booking->save();

        // $approval = $booking->is_approved == 1 ? 'Approved' : 'Rejected';
        // $status = $booking->need_approval == 1 ? 'Pending' : 'Done';


        return new JsonResponse([
            'status'=>true,
            'message'=>'Success',
            'data'=>null
            // [
            // 'id'=>$booking->id,
            // 'driver'=>$booking->driver,
            // 'applicant'=>$booking->applicant,
            // 'is_approval'=>$approval,
            // 'need_approval'=>$status,
            // 'start_book'=>$booking->start_book,
            // 'end_book'=>$booking->end_book,
            // 'apporved_by'=>[
            //     'id_users'=>$booking->approver->id??null,
            //     'name'=>$booking->approver->username??null
            // ],
            // 'vehicle'=>[
            //     'id_vehicle'=>$booking->vehicle->id??null,
            //     'name'=>$booking->vehicle->name??null
            // ]
            // ]
        ]);
    }


}
