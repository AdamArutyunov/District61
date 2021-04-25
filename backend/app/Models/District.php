<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;
    protected $fillable = ["name", "rating", "coords"];

    public function reports()
    {
        return $this->hasMany(Report::class, "district_id", "id");
    }

    public function user(){
        return $this->belongsToMany(User::class);
    }
}
