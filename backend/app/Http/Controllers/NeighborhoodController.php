<?php

namespace App\Http\Controllers;

use App\Models\Neighbourhood;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class NeighborhoodController extends Controller{
    public function index(): JsonResponse
    {
        return response()->json(Neighbourhood::with("reports")->get());
    }

    public function getNeighbourhood($id): JsonResponse
    {
        $neighbourhood = Neighbourhood::with("reports")->findOrFail($id);
        return response()->json($neighbourhood);
    }

}
