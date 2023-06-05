<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $departments = Department::paginate(15);
        } else {
            $departments['data'] = Department::all();
        }

        $responseData['success'] = true;
        $responseData['data']['departments'] = $departments;

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
            $department = new Department;

            $department->name = $request->name;

            $department->save();

            $responseData['success'] = true;
            $responseData['data']['department'] = $department;
            $responseData['message'] = 'Département créé avec succès';

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

        $department = Department::find($id);
        
        if (!$department) {
            $responseData['success'] = false;
            $responseData['message'] = 'Département non trouvé';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['department'] = $department;

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

        $department = Department::find($id);
        
        if (!$department) {
            $responseData['success'] = false;
            $responseData['message'] = 'Département non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $department->name = $request->name;

            $department->save();

            $responseData['success'] = true;
            $responseData['data']['department'] = $department;
            $responseData['message'] = 'Département mis à jour avec succès';

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

        $department = Department::find($id);
        
        if (!$department) {
            $responseData['success'] = false;
            $responseData['message'] = 'Département non trouvé';

            return response()->json($responseData, 404);
        }

        $department->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Département supprimé avec succès';

        return response()->json($responseData, 200);
    }
}
