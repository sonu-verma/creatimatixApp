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

    public function getNearByTurf(){
        $turfs = Turf::where("status", 1)->with(['sports', 'images'])->inRandomOrder()->limit(5)->get();
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

    public function storeTurf(Request $request){
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:turfs,slug,' . $request->id,
            'location' => 'required|string',
            'address' => 'required|string',
            'timing' => 'required|string',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'benefits' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'rules' => 'nullable',
        ]);
        
        $input = $request->all();
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
            $value = preg_replace('/[\'",`]/', '', $value);
            }
        });
        $request->merge($input);


        if($request->has('id') && $request->id != null){
            $turf = Turf::where('id', $request->id)->first();
            $turfStore = true;
            $isRedirect = false;
        } else {
            $turf = new Turf();
        }

        $turf->name = $request->name;
        $turf->slug = $request->slug;
        $turf->location = $request->location;
        $turf->address = $request->address;
        $turf->timing = $request->timing; 
        $turf->pricing = $request->pricing; 
        $turf->description = $request->description;
        $turf->features = $request->features;
        $turf->benefits = $request->benefits;
        $turf->latitude = $request->latitude;
        $turf->longitude = $request->longitude;
        $turf->status = $request->is_active;
        $turf->rules = $request->rules;
        $turf->save();
         
        return response()->json( ["slug" => $turf->slug, 'message' => 'Turf created successfully.']);
    }
}
