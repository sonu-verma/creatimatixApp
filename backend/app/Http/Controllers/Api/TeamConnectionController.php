<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamUserConnection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamConnectionController extends Controller
{
    public function sendRequest(Request $request, Team $team)
    {
        $userId = auth()->id();

        if ($team->id_user === $userId) {
            return ResponseHelper::error(status: "error",message: 'You cannot request to your own team.', statusCode: 400);
        }

        $exists = TeamUserConnection::where('id_team', $team->id)->where('id_user', $userId)->exists();
        if ($exists) {
            return ResponseHelper::error(status: "error",message: 'Request already sent.', statusCode: 400);
        }

        TeamUserConnection::create([
            'id_team' => $team->id,
            'id_user' => $userId,
            'status' => 'pending',
        ]);

        return ResponseHelper::success(status: "success", message: 'Request sent successfully.', statusCode: 200, data: []);
    }

    public function accept($id)
    {
        $connection = TeamUserConnection::findOrFail($id);

        // Only team creator can accept
        if ($connection->team->id_user !== auth()->id()) {
            ResponseHelper::error(status: "error", message: 'Unauthorized.', statusCode: Response::HTTP_FORBIDDEN);
        }

        $connection->status = 'accepted';
        $connection->save();

        return ResponseHelper::success(status: "success", message: 'Connection accepted.', statusCode: 200, data: []);
    }

    public function reject($id)
    {
        $connection = TeamUserConnection::findOrFail($id);

        if ($connection->team->id_user !== auth()->id()) {
            ResponseHelper::error(status: "error", message: 'Unauthorized.', statusCode: Response::HTTP_FORBIDDEN);
        }

        $connection->status = 'rejected';
        $connection->save();

        return ResponseHelper::success(status: "success", message: 'Connection rejected.', statusCode: 200, data: []);
    }

    public function myConnections()
    {
        $data = auth()->user()->acceptedConnections()->with('team')->get();
        return ResponseHelper::success(status: "success", message: 'Request sent successfully.', statusCode: 200, data: $data);
         
    }

    public function myRequests()
    {
        $user = auth()->user();
        $teams = Team::where('id_user', $user->id)->pluck('id');
        $myrequests = TeamUserConnection::whereIn('id_team', $teams)->with('team')->where('status', 'pending')->orderBy("id", 'desc')->get();
        // dd($myrequests);
        // $data = auth()->user()->pendingOrRejectedRequests()->with('team')->get();
        return ResponseHelper::success(status: "success", message: 'Request sent successfully.', statusCode: 200, data: $myrequests);
         
    }
}
