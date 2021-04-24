<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neighbourhood extends Model
{
    protected $table = "districts";
    public $timestamps = false;
    protected $fillable = ["name", "rating", "coords"];
    public function reports(){
        return $this->hasMany(Report::class, "district_id", "id");
    }
}
