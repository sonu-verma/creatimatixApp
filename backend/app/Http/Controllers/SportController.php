<?php

namespace App\Http\Controllers;

use App\Models\Admin\SportType;
use App\Models\Sport;
use Illuminate\Http\Request;
use stdClass;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSports(Request $request)
    {
  
        $request->validate([
            "turfId" => "required",
            "id_sport" => "required",
            "dimension" => "required",
            "capacity" => "required",
            "rate_per_hour" => "required",
            "rules" => "nullable"
        ]);
    
        $sportType = SportType::where('id', $request->id_sport)->first();


        if($request->has('sportId') && $request->sportId != null){
            $sport = Sport::where('id', $request->sportId)->first();
            if(!$sport){
                $sport = new Sport();
            }
        }else{
            $sport = new Sport(); 
        }

        
        $sport->id_turf = $request->turfId;
        $sport->name = $sportType?$sportType?->name: null;
        $sport->id_sport = $request->id_sport;
        $sport->rate_per_hour = $request->rate_per_hour;            
        $sport->dimensions = $request->dimension;
        $sport->capacity = $request->capacity;
        $sport->rules = $request->rules;
        $sport->status = $request->status;
        $sport->save();

        $sports = Sport::where('id_turf', $request->turfId)->get();
        // $model = new stdClass();
        // $model->sports = $sports;

        return response()->json([
            'success' => true,
            'message' => 'Sport added successfully',
            'html' => view('admin.turfs.includes.sportList', ['sports' => $sports])->render()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sport $sport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editSports(Sport $sport)
    {
        if($sport){
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'Sport fetched successfully',
                'sport' => $sport
            ]);
        }

        return response()->json([
            'statusCode' => 403,
            'success' => false,
            'message' => 'Sport data not found.',
            'sport' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSports(Request $request, Sport $sport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteSports(Sport $sport)
    {
        //
    }
}
