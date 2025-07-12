<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use App\Models\TeamUserConnection;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if(!$user){
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }
        
        $teams = Team::where('id_user', $user->id)->with('user')->orderBy('id', 'desc')->get();
        if($teams){
            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'Teams fetched successfully',
                data: $teams
            );
        }else{
            return ResponseHelper::error(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'No teams found',
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamCreateRequest $request)
    {
       try{
            $user = Auth::user();
            if(!$user){
                return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
            }
            
            $team = new Team();
            $team->name = $request->name;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('team_images', $filename, 'public');
                $team->logo = $filename; 
            }
            $team->id_user = $user->id;
            $team->short_desc = $request->short_desc;
            $team->save();

            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'User updated successfully',
                data: $team
            );
       } catch(Exception $e) {
            return ResponseHelper::error(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'Something went wrong, please try again later, '.$e->getMessage(),
            );
       }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        $team = Team::where('id_user', $loggedInUser->id)->find($id);
        if ($team) {
            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'Team fetched successfully',
                data: $team
            );
        } else {
            return ResponseHelper::error(
                statusCode: Response::HTTP_NOT_FOUND,
                message: 'Team not found',
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamUpdateRequest $request, string $id)
    {

        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        $team = Team::where('id_user', $loggedInUser->id)->find($id);


        $isAlreadyExists = Team::where('name', $request->name)->where('id_user','!=', $loggedInUser->id)->first();

        if($isAlreadyExists){
            return ResponseHelper::error(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'Team name already exists',
            );
        }

        if($team){
            $team->name = $request->name;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('team_images', $filename, 'public');
                $team->logo = $filename; 
            }
            $team->short_desc = $request->short_desc;
            $team->save();

            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'Team updated successfully',
                data: $team
            ); 
        }else{
            return ResponseHelper::error(
                statusCode: Response::HTTP_NOT_FOUND,
                message: 'Team not found',
            );
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function userTeamConnection(Request $request){
        $idUser = Auth::user()->id;
        // dd($request->header());
        $teams =new Team();
        if($idUser){
            $teams = $teams->where('id_user','!=', $idUser)->with(['connection', 'user']);
        }
        $teams = $teams->orderBy('id', 'desc')->get();
        if($teams){
            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'Teams fetched successfully',
                data: $teams
            );
        }else{
            return ResponseHelper::error(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'No teams found',
            );
        }
    }

    public function UserConnection(Request $request){
        $idUser = $request->get('id_user', null);
        $teams = TeamUserConnection::where('id_user', $idUser)->with('team')->orderBy('id', 'desc')->get();;
       
        if($teams){
            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'Teams fetched successfully',
                data: $teams
            );
        }else{
            return ResponseHelper::error(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'No teams found',
            );
        }
    }
}
