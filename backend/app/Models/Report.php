<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ["district_id", "body", "is_good", "likes", "dislikes"];
    public $timestamps = false;
}
