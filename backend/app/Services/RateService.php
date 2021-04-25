<?php

namespace App\Services;


use App\Models\Reaction;

class RateService
{
    public static function reEstimate($district)
    {
        $rate = self::estimate($district);
        $district->rating = $rate;
        $district->save();
    }

    private static function estimate($district)
    {
        $reports = $district->reports;

        $top = 0;
        $down = 0;

        foreach ($reports as $report) {
            $likes = Reaction::where("report_id", $report->id)->where("type", "like")->count();
            $dislikes = Reaction::where("report_id", $report->id)->where("type", "dislike")->count();
            $s = ($likes + $dislikes == 0) ? 1 : ($likes + $dislikes);
            $w = 1;
            if ($s > 0) {
                $w = pow($likes / $s, 1) * pow($s, 1 / 4);
            }

            $down += $w;
            $top += $w * ($report->is_good ? 1 : (-1));
        }
        if ($down == 0) {
            return 5.5;
        }

        return ($top / $down) * 4.5 + 5.5;

    }

}
