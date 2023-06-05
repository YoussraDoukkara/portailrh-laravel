<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $documentCategories = DocumentCategory::paginate(15);
        } else {
            $documentCategories['data'] = DocumentCategory::all();
        }

        $responseData['success'] = true;
        $responseData['data']['document_categories'] = $documentCategories;

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
            $documentCategory = new DocumentCategory;

            $documentCategory->name = $request->name;

            $documentCategory->save();

            $responseData['success'] = true;
            $responseData['data']['document_category'] = $documentCategory;
            $responseData['message'] = 'La catégorie de document créée avec succès';

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

        $documentCategory = DocumentCategory::find($id);
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvée';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['document_category'] = $documentCategory;

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

        $documentCategory = DocumentCategory::find($id);
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvée';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $documentCategory->name = $request->name;

            $documentCategory->save();

            $responseData['success'] = true;
            $responseData['data']['team'] = $documentCategory;
            $responseData['message'] = 'La catégorie de document a été mise à jour avec succès';

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

        $documentCategory = DocumentCategory::find($id);
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvée';

            return response()->json($responseData, 404);
        }

        $documentCategory->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'La catégorie de document supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
