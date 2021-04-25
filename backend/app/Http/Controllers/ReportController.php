<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Report;
use App\Services\RateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'district_id' => 'required|integer',
            'body' => 'required|max:5000',
            'is_good' => 'required|boolean'
        ]);
        $report = new Report();
        $report->fill($request->all());
        $report->save();
        $district = District::with("reports")->where("id", $request->get("district_id"))->first();
        RateService::reEstimate($district);
        return response()->json(["message" => "ok", "data" => $report->fresh()]);
    }

    public function createFeedback(Request $request): JsonResponse
    {

        $report = Report::with("district")->findOrFail($request->get("report_id"));
        $feedbackType = $request->get("type");
        switch ($feedbackType) {
            case "like":
                $report->likes++;
                break;
            case "dislike":
                $report->dislikes++;
        }

        $district = District::with("reports")->where("id", $report->district_id)->first();
        RateService::reEstimate($district);
        $report->save();
        return response()->json("ok");
    }

    public function destroy($id){
        Report::destroy($id);
        return response()->json("ok");
    }
}
