<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Turf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TurfController extends Controller
{
    public function availableTurfs(Request $request){

        $sportType = $request->input('sportType'); // could be id | slug | name
        $searchTerm = $request->input('city'); // could be id | slug | name

        $turfs = Turf::where("status", 1)->with(['sports.sportType', 'images'])
        
        ->when($sportType , function ($q) use ($sportType) {
            $q->whereHas('sports.sportType', function ($sq) use ($sportType) {
                if (is_numeric($sportType)) {
                    $sq->where('id', (int) $sportType);
                } else {
                    // match by slug or name loosely
                    $sq->where(function ($tq) use ($sportType) {
                        $tq->where('slug', $sportType)
                           ->orWhere('name', 'like', '%' . $sportType . '%');
                    });
                }
            });
        })
        // Free-text search across turf columns you care about
        ->when($searchTerm, function ($q) use ($searchTerm) {
            $q->where(function ($sq) use ($searchTerm) {
                $sq->where('name', 'like', '%' . $searchTerm . '%')
                   ->orWhere('location', 'like', '%' . $searchTerm . '%')
                   ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        })
        ->withCount([
            'approvedReviews as total_reviews',
        ])
        ->withAvg([
            'approvedReviews as average_rating' => function ($query) {
                $query->where('status', true);
            },
        ], 'rating')

        // $query = vsprintf(str_replace(array('?'), array('\'%s\''), $turfs->toSql()), $turfs->getBindings());
        // dd($query);
        ->paginate(10);
        return response()->json([
            "statusCode" => Response::HTTP_OK,
            "turfs" => $turfs,
            // "query" => $query
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
        $turf = Turf::where("status", 1)->where('slug', $slug)->with(['sports.sportType', 'images', 'approvedReviews.user:id,name,profile'])->first();
        
        if(!$turf){
            return ResponseHelper::error(status: 'error', message: 'Turf not available, please contact admin.');
        }
        
        // Add review statistics to turf data
        $turf->rating = [
            'average' => (int)$turf->average_rating,
            'count' => $turf->total_reviews
        ];

        // Get booked slots if date is provided
        $date = $request->get('date');
        if ($date) {
            $bookedSlots = \App\Models\SlotBooking::where('turf_id', $turf->id)
                ->where('date', $date)
                ->where('status', 1) // 1 = confirmed
                ->get()
                ->map(function($booking) {
                    return [
                        'start_slot_value' => $booking->start_slot_value,
                        'end_slot_value' => $booking->end_slot_value,
                        'start_time' => $booking->start_time,
                        'end_time' => $booking->end_time,
                        'duration' => $booking->duration,
                        'booking_id' => $booking->id
                    ];
                });
            
            $turf->booked_slots = $bookedSlots;
            $turf->booking_stats = [
                'total_booked' => $bookedSlots->count(),
                'date' => $date
            ];
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
