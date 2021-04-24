<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\JsonResponse;


class DistrictController extends Controller{

    public function index(): JsonResponse
    {
        return response()->json(District::with("reports")->get());
    }

    public function getDistrict($id): JsonResponse
    {
        $neighbourhood = District::with("reports")->findOrFail($id);
        return response()->json($neighbourhood);
    }

}
