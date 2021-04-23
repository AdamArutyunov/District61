<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $report = new Report();
        $report->fill($request->all());
        $report->save();
        return response()->json(["message" => "ok", "data" => $report->fresh()]);
    }

    public function createFeedback(Request $request): JsonResponse
    {
        $report = Report::findOrFail($request->get("report_id"));
        $feedbackType = $request->get("type");
        switch ($feedbackType){
            case "like":
                $report->likes++;
                break;
            case "dislike":
                $report->dislikes++;
        }
        $report->save();
        return response()->json("ok");
    }
}
