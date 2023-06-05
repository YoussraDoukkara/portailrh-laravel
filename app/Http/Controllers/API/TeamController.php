<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $teams = Team::paginate(15);
        } else {
            $teams['data'] = Team::all();
        }

        $responseData['success'] = true;
        $responseData['data']['teams'] = $teams;

        return response()->json($responseData, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responseData = [];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $team = new Team;

            $team->name = $request->name;

            $team->save();

            $responseData['success'] = true;
            $responseData['data']['team'] = $team;
            $responseData['message'] = 'Équipe créée avec succès';

            return response()->json($responseData, 200);
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $responseData = [];

        $team = Team::find($id);
        
        if (!$team) {
            $responseData['success'] = false;
            $responseData['message'] = 'Équipe non trouvée';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['team'] = $team;

        return response()->json($responseData, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $responseData = [];

        $team = Team::find($id);
        
        if (!$team) {
            $responseData['success'] = false;
            $responseData['message'] = 'Équipe non trouvée';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $team->name = $request->name;

            $team->save();

            $responseData['success'] = true;
            $responseData['data']['team'] = $team;
            $responseData['message'] = 'L\'équipe a été mise à jour avec succès';

            return response()->json($responseData, 200);
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $responseData = [];

        $team = Team::find($id);
        
        if (!$team) {
            $responseData['success'] = false;
            $responseData['message'] = 'Équipe non trouvée';

            return response()->json($responseData, 404);
        }

        $team->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Équipe supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
