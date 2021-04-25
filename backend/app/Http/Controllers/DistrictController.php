<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class DistrictController extends Controller
{

    public function index(): JsonResponse
    {
        // TODO ПОФИКСИТЬ ЭТО ЧУДОВИЩЕ
        $districts = District::with("reports.reactions")->get();
        foreach ($districts as $district) {
            foreach ($district->reports as $report){
                $report->login = User::find($report->user_id)->login;
                $report->dislikes = $report->reactions->where("type", "dislike")->count();
                $report->likes = $report->reactions->where("type", "like")->count();
            }
        }
        return response()->json($districts);
    }

    public function getDistrict($id): JsonResponse
    {
        $district = District::with("reports.reactions")->findOrFail($id);
        foreach ($district->reports as $report){
            $report->dislikes = $report->reactions->where("type", "dislike")->count();
            $report->likes = $report->reactions->where("type", "like")->count();
        }
        return response()->json($district);
    }

}
