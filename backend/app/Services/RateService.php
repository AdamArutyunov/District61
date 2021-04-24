<?php

namespace App\Services;


class RateService
{
    private static function estimate($district)
    {
        $reports = $district->reports;

        $top = 0;
        $down = 0;

        foreach ($reports as $report) {
            $s = $report->likes + $report->dislikes;
            $w = 1;
            if ($s > 0) {
                $w = pow($report->likes / $s, 1) * pow($s, 1 / 4);
            }

            $down += $w;
            $top += $w * $report->is_good;
        }

        return ($top / $down) * 10;

    }


    public static function reEstimate($district)
    {
        $rate = self::estimate($district);
        $district->rating = $rate;
        $district->save();
    }

}
