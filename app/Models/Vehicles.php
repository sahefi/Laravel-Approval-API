<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory , HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $fillable = ['name','type','fuel_consumption','service_schedule'];

    protected $hidden = ['created_at','updated_at'];
    public function booking(){
        return $this->hasOne(Vehicles::class,'id','id_vehicle');
    }

}
