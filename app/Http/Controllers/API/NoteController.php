<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $notes = Note::where('user_id', Auth::id())->orderBy('id', 'desc')->with('user')->paginate(5);
        } else {
            $notes['data'] = Note::where('user_id', Auth::id())->orderBy('id', 'desc')->with('user')->all();
        }

        $responseData['success'] = true;
        $responseData['data']['notes'] = $notes;

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
            'body' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $note = new Note;

            $note->user_id = Auth::id();
            $note->body = $request->body;

            $note->save();

            $responseData['success'] = true;
            $responseData['data']['note'] = $note;
            $responseData['message'] = 'Note créée avec succès';

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

        $note = Note::where('user_id', Auth::id())->where('id', $id)->with('user')->first();
        
        if (!$note) {
            $responseData['success'] = false;
            $responseData['message'] = 'Note non trouvée';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['note'] = $note;

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

        $note = Note::where('user_id', Auth::id())->where('id', $id)->first();
        
        if (!$note) {
            $responseData['success'] = false;
            $responseData['message'] = 'Note non trouvée';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $note->user_id = Auth::id();
            $note->body = $request->body;

            $note->save();

            $responseData['success'] = true;
            $responseData['data']['note'] = $note;
            $responseData['message'] = 'La note a été mise à jour avec succès';

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

        $note = Note::where('user_id', Auth::id())->where('id', $id)->first();
        
        if (!$note) {
            $responseData['success'] = false;
            $responseData['message'] = 'Note non trouvée';

            return response()->json($responseData, 404);
        }

        $note->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Note supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
