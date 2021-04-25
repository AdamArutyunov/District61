<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Reaction;
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
            'is_good' => 'required|boolean',
        ]);
        $report = new Report();
        $report->fill($request->all());
        $report->user_id = auth()->user()->id;
        $report->save();
        $district = District::with("reports")->where("id", $request->get("district_id"))->first();
        RateService::reEstimate($district);
        return response()->json(["message" => "ok", "data" => $report->fresh()]);
    }

    public function createFeedback(Request $request): JsonResponse
    {

        $this->validate($request, [
            "report_id" => "required|integer",
            "type" => "required",
        ]);
        if (Reaction::where("user_id", $request->user_id)->where("report_id", $request->get("report_id"))->count() > 0){
            return response()->json(["error" => true, "message" => "Вы уже делали реакцию на этот отзыв"]);
        }
        $r = new Reaction();
        $r->fill($request->all());
        $r->user_id = auth()->user()->id;
        $r->save();
        $district = District::with("reports")->where("id", Report::find($request->get("report_id"))->district_id)->first();
        RateService::reEstimate($district);
        return response()->json("ok");
    }

    public function destroy($id){
        Report::destroy($id);
        return response()->json("ok");
    }
}
