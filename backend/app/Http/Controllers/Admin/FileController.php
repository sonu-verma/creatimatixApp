<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turf;
use App\Models\TurfImage;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function actionCreate(Request $request, $type = null){
        $response = match ($type) {
            'turf' => $this->turfImage($request),
            default => ['error' => 'Invalid type provided']
        };

        return $response;
    }

    private function turfImage(Request $request){
        $request->validate([
            'id_turf' => "required"
        ]);

        $responses = [];

        $imagesCount = count($request->file('images', []));
        
        $turf  = Turf::where('id', $request->id_turf)->get()->first();

        if($turf){

            $lastSort = TurfImage::where('id_turf', $turf->id)
            ->get()
            ->last();
            $sortIndex = 0;
            if($lastSort){
                $sortIndex = $lastSort->sort + 1;
            }

            $defaultImage = TurfImage::where('id_turf', $turf->id)
            ->where('is_default', 1)
            ->get()
            ->first();
            $defaultStatus = 1;
            
            if($defaultImage){
                $defaultStatus = 0;
            }

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach($files as $file){
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('admin/uploads/turfs/'), $filename);;

                    // Save the image path to the database
                    $image = new TurfImage();
                    $image->image_name = $filename;
                    $image->id_turf = $turf?->id;
                    $image->is_default = $defaultStatus;
                    $image->sort = $sortIndex;
                    $image->save();


                    $data = new \stdClass();
                    $data->image_url = asset('admin/uploads/turfs/').'/'.$filename;
                    $data->id = $image->id;
                    $data->image_name = $file->getClientOriginalName();
                    $data->sort_order = $sortIndex;
                    $data->is_default = $defaultStatus;

                    $responses[] = $data;
                    $sortIndex++;
                }
            }
        }
        return view('admin.turfs.includes.image', [
            'images' => $responses
        ])->render();
    }
}
