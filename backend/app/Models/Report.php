<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    public $timestamps = false;
    protected $fillable = ["district_id", "body", "is_good", "likes", "dislikes", "user_id"];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, "district_id", "id");
    }

    public function reactions(){
        return $this->hasMany(Reaction::class);
    }


}
