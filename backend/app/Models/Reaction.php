<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    public $timestamps = false;
    protected $fillable = ["report_id", "type", "user_id"];
    public function report(){
        return $this->belongsToMany(Report::class);
    }
}
