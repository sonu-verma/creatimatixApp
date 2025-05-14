<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TurfController extends Controller
{
    public function availableTurfs(){
        $turfs = Turf::where("status", 1)->with('sports')->paginate(10);
        return response()->json([
            "statusCode" => Response::HTTP_OK,
            "turfs" => $turfs
        ]);
    }
}
