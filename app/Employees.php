<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Companies;


class Employees extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'company_id', 'email', 'phone',
    ];



    public function company()
    {
        return $this->hasOne(Companies::class, 'id', 'company_id');
    }

}
