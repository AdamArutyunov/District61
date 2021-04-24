<?php

namespace App\Models;

use App\Http\Controllers\DistrictController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = ["district_id", "body", "is_good", "likes", "dislikes"];
    public $timestamps = false;

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, "district_id", "id");
    }
}
