<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersQuestion extends Model
{
    //
    protected $fillable = ['deleted_time'];
    
    protected $primaryKey = 'user_id';
}
