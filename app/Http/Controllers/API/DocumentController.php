<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DocumentCategory $documentCategory)
    {
        $responseData = [];
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvé';

            return response()->json($responseData, 404);
        }

        if ($request->has('page')) {
            $documents = Document::where('document_category_id', $documentCategory->id)->paginate(15);
        } else {
            $documents['data'] = Document::where('document_category_id', $documentCategory->id)->all();
        }

        $responseData['success'] = true;
        $responseData['data']['documents'] = $documents;

        return response()->json($responseData, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DocumentCategory $documentCategory)
    {
        $responseData = [];
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $document = new Document;

            $document->document_category_id = $documentCategory->id;
            $document->name = $request->name;

            $document->save();

            if ($request->attachments) {
                $document->addMultipleMediaFromRequest(['attachments'])->each(function ($fileAdder) {
                    $fileAdder
                        ->toMediaCollection('documents');
                });
            }

            $responseData['success'] = true;
            $responseData['data']['document'] = $document;
            $responseData['message'] = 'Document créée avec succès';

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
    public function show(DocumentCategory $documentCategory, $id)
    {
        $responseData = [];
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvé';

            return response()->json($responseData, 404);
        }

        $document = Document::find($id);
        
        if (!$document) {
            $responseData['success'] = false;
            $responseData['message'] = 'Document non trouvée';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['document'] = $document;

        return response()->json($responseData, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentCategory $documentCategory, $id)
    {
        $responseData = [];
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvé';

            return response()->json($responseData, 404);
        }

        $document = Document::find($id);
        
        if (!$document) {
            $responseData['success'] = false;
            $responseData['message'] = 'Document non trouvée';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if (!$validator->fails()) {
            $document->document_category_id = $documentCategory->id;
            $document->name = $request->name;

            $document->save();

            $responseData['success'] = true;
            $responseData['data']['document'] = $document;
            $responseData['message'] = 'Le document a été mise à jour avec succès';

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
    public function destroy(DocumentCategory $documentCategory, $id)
    {
        $responseData = [];
        
        if (!$documentCategory) {
            $responseData['success'] = false;
            $responseData['message'] = 'La catégorie de document non trouvé';

            return response()->json($responseData, 404);
        }

        $document = Document::find($id);
        
        if (!$document) {
            $responseData['success'] = false;
            $responseData['message'] = 'Document non trouvée';

            return response()->json($responseData, 404);
        }

        $document->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Document supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
