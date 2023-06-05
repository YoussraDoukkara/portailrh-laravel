<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $posts = Post::orderBy('id', 'desc')->with('user')->paginate(5);
        } else {
            $posts['data'] = Post::orderBy('id', 'desc')->get();
        }

        $responseData['success'] = true;
        $responseData['data']['posts'] = $posts;

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
            $post = new Post;

            $post->user_id = Auth::id();
            $post->body = $request->body;

            $post->save();

            $responseData['success'] = true;
            $responseData['data']['post'] = $post;
            $responseData['message'] = 'Publication créée avec succès';

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

        $post = Post::where('id', $id)->with('user')->first();
        
        if (!$post) {
            $responseData['success'] = false;
            $responseData['message'] = 'Publication non trouvée';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['post'] = $post;

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

        $post = Post::find($id);
        
        if (!$post) {
            $responseData['success'] = false;
            $responseData['message'] = 'Publication non trouvée';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $post->user_id = Auth::id();
            $post->body = $request->body;

            $post->save();

            $responseData['success'] = true;
            $responseData['data']['post'] = $post;
            $responseData['message'] = 'La publication a été mise à jour avec succès';

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

        $post = Post::find($id);
        
        if (!$post) {
            $responseData['success'] = false;
            $responseData['message'] = 'Publication non trouvée';

            return response()->json($responseData, 404);
        }

        $post->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Publication supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
