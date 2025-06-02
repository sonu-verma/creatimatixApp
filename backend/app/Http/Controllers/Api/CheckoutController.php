<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function bookTurf(Request $request){
        try{
            $auth = Auth::user();
            $validatedData = $request->validate([
                'selectedDate' => 'required|date',
                'selectedSlots' => 'required|array',
                'totalPrice' => 'required|numeric',
            ]);

            $booking = new Booking();
            $booking->id_user = $auth->id;
            $booking->selected_date = $validatedData['selectedDate'];
            $booking->selected_slots = json_encode($validatedData['selectedSlots']);
            $booking->total_price = $validatedData['totalPrice'];
            $booking->save();

            return ResponseHelper::success(status: 'succcess', message: 'Booking successful',data:$booking, statusCode:201);
        }catch(Exception $e){
            return ResponseHelper::error(status: 'error', message: $e->getMessage());
        }
    }
}
