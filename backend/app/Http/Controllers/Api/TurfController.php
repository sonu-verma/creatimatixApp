<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Turf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TurfController extends Controller
{
    public function availableTurfs(){
        $turfs = Turf::where("status", 1)->with(['sports', 'images'])->paginate(10);
        return response()->json([
            "statusCode" => Response::HTTP_OK,
            "turfs" => $turfs
        ]);
    }
    public function getTurf(Request $request, $slug = null){
        // $slug = $request->get('slug', null);
        $turf = Turf::where("status", 1)->where('slug', $slug)->with(['sports', 'images'])->first();
        
        if(!$turf){
            return ResponseHelper::error(status: 'error', message: 'Turf not available, please contact admin.');
        }
        return ResponseHelper::success(status: 'success', message: "Data loaded", data: $turf);
     
    }
}
