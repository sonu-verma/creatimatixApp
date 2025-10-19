<?php

namespace App\Http\Controllers;

use App\Models\Admin\SportType;
use App\Models\Turf;
use App\Models\TurfImage;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Dumper\DumperInterface;

class TurfController extends Controller
{
    public function index()
    {
        $turfs = Turf::paginate(10);
        session([
            'turf_id' => '',
            'turf_step' => ''
        ]);

        return view('admin.turfs.index', [
            'turfs' => $turfs
        ]);
       
    }

    public function create()
    {

        $sportTypes = SportType::all();
        $var = [
            'model' => null,
            'step' => session('turf_step', 'basic'),
            'turf_id' => session('turf_id', ''),
            'route' => '',
            'sportTypes' => $sportTypes,
        ];
        return view('admin.turfs.form', $var);
    }

    public function edit(Request $request, $id = null)
    {
        $turf = Turf::where('id', $id)->with('images')->with('sports')->first();
        $sportTypes = SportType::all();
        if($turf){
            $default_val = session('is_redirect') ? 'images' : 'basic';
            $var = [
                'model' => $turf,
                'step' => $default_val,
                'route' => Route("turf.store.sport"),
                'sports' => $turf?->sports,
                'turf_id' => session('turf_id'), 
                'sportTypes' => $sportTypes,
            ];
            session('is_redirect', false);
            return view('admin.turfs.form', $var);
        }else{
            return redirect()->route('turfs');
        }
        
    }

   
    public function storeBasic(Request $request)
    {

        $turfStore = false;
        $isRedirect = true;
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
            'status' => 'required',
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
        $turf->status = $request->status;
        $turf->rules = $request->rules;
        $turf->save();
           
        session([
            'turf_id' => $turf->id,
            'turf_step' => $turfStore ? 'basic' :'images',
            'is_redirect' => $isRedirect
        ]);

        return redirect()->route('turf.edit', ["id" => $turf->id]);
    }

    public function deleteImage(Request $request, $id){
        $image = TurfImage::where('id', $id)->first();
        if($image){
            $image->delete();
        }

        return response()->json([
            'message' => 'Delete successfully.',
            'id' => $id,
        ]);
    }
}
