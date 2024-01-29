<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $fillable = ['name'];

    protected $hidden = ['created_at','updated_at','deleted_at'];
    public function user(){
        return $this->hasMany(Users::class,'id_role');
    }
}
