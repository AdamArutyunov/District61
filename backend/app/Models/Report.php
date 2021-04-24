<?php

namespace App\Models;

use App\Http\Controllers\NeighborhoodController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = ["district_id", "body", "is_good", "likes", "dislikes"];
    public $timestamps = false;

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(NeighborhoodController::class, "id", "district_id");
    }
}
