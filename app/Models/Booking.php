<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory,HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';
    public $fillable = ['driver', 'id_vehicle', 'applicant', 'id_approver','status', 'start_book', 'end_book'];

    protected $hidden = ['created_at','updated_at'];
    protected $date = ['start_book,end_book'];
    public function vehicle(){
        return $this->hasOne(Vehicles::class,'id','id_vehicle');
    }
    public function approver(){
        return $this->hasOne(Users::class,'id','id_approver');
    }
}
