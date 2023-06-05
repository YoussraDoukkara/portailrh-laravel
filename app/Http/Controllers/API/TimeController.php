<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $times = Time::paginate(15);
        } else {
            $times['data'] = Time::all();
        }

        $responseData['success'] = true;
        $responseData['data']['times'] = $times;

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
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'is_breakable' => 'required|boolean',
            'is_leave' => 'required|boolean',
            'is_rest' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $time = new Time;

            $time->name = $request->name;
            $time->check_in = $request->check_in;
            $time->check_out = $request->check_out;
            $time->is_breakable = $request->is_breakable;
            $time->is_leave = $request->is_leave;
            $time->is_rest = $request->is_rest;

            $time->save();

            $responseData['success'] = true;
            $responseData['data']['time'] = $time;
            $responseData['message'] = 'Heure créée avec succès';

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

        $time = Time::find($id);
        
        if (!$time) {
            $responseData['success'] = false;
            $responseData['message'] = 'Heure non trouvé';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['time'] = $time;

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

        $time = Time::find($id);
        
        if (!$time) {
            $responseData['success'] = false;
            $responseData['message'] = 'Heure non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'is_breakable' => 'required|boolean',
            'is_leave' => 'required|boolean',
            'is_rest' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $time->name = $request->name;
            $time->check_in = $request->check_in;
            $time->check_out = $request->check_out;
            $time->is_breakable = $request->is_breakable;
            $time->is_leave = $request->is_leave;
            $time->is_rest = $request->is_rest;

            $time->save();

            $responseData['success'] = true;
            $responseData['data']['time'] = $time;
            $responseData['message'] = 'Heure mise à jour avec succès';

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

        $time = Time::find($id);
        
        if (!$time) {
            $responseData['success'] = false;
            $responseData['message'] = 'Heure non trouvé';

            return response()->json($responseData, 404);
        }

        $time->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Heure supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
