<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $designations = Designation::paginate(15);
        } else {
            $designations['data'] = Designation::all();
        }

        $responseData['success'] = true;
        $responseData['data']['designations'] = $designations;

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
            $designation = new Designation;

            $designation->name = $request->name;

            $designation->save();

            $responseData['success'] = true;
            $responseData['data']['designation'] = $designation;
            $responseData['message'] = 'Désignation créée avec succès';

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

        $designation = Designation::find($id);
        
        if (!$designation) {
            $responseData['success'] = false;
            $responseData['message'] = 'Désignation non trouvée';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['designation'] = $designation;

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

        $designation = Designation::find($id);
        
        if (!$designation) {
            $responseData['success'] = false;
            $responseData['message'] = 'Désignation non trouvée';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $designation->name = $request->name;

            $designation->save();

            $responseData['success'] = true;
            $responseData['data']['designation'] = $designation;
            $responseData['message'] = 'Désignation mise à jour avec succès';

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

        $designation = Designation::find($id);
        
        if (!$designation) {
            $responseData['success'] = false;
            $responseData['message'] = 'Désignation non trouvée';

            return response()->json($responseData, 404);
        }

        $designation->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Désignation supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
