<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use HasFactory,HasUuids;
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $fillable = ['username','email','password','id_role'];
    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Roles::class,'id_role');
    }

    public function approver(){
        return $this->hasMany(Booking::class,'id','id_approver');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
                'id' => $this->id,
                'role' => optional($this->role)->name,
            ];
    }
}
