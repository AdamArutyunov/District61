<?php

namespace App\Services;


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

            $s = ($report->likes + $report->dislikes == 0) ? 1 : ($report->likes + $report->dislikes);
            $w = 1;
            if ($s > 0) {
                $w = pow($report->likes / $s, 1) * pow($s, 1 / 4);
            }

            $down += $w;
            $top += $w * $report->is_good;
        }
        if ($down == 0) {
            return 5.5;
        }
        return ($top / $down) * 10;

    }

}
