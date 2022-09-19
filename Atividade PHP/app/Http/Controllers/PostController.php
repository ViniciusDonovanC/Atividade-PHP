<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /*
        Mostrar Todos os Itens
    */

    public function indexPost(Request $request)
    {
        return Post::all();
    }


    public function storePost(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'descricao' => ['required', 'max:200'],
            'titulo' => ['required', 'max:20'],
            'usuario' => ['required', 'max:20'],

        ]);

        if (!$validated->fails()) {
            $post = new Post;
            $post->descricao = $request->descricao;
            $post->titulo = $request->titulo;
            $post->usuario = $request->usuario;
            $post->save();

            return response()->json([
                "message" => "Post Created"
            ], 201);
        }

        return response()->json([
            "message" => $validated->errors()->all()
        ], 500);
    }


    public function showPost(Request $request, $id)
    {
        if (Post::where('id', $id)->exists()) {
            $post = Post::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($post, 200);
        } else { 
            return response()->json([
                "message" => "Post Not Found Or Does Not Exist"
            ], 404);
        }
    }


    public function editPost(Request $request, $id)
    {
        if (Post::where('id', $id)->exists()) {

            $post = Post::find($id);
            $post->usuario = is_null($request->usuario) ? $post->usuario : $request->usuario;
            $post->titulo = is_null($request->titulo) ? $post->titulo : $request->titulo;
            $post->descricao = is_null($request->descricao) ? $post->descricao : $request->descricao;
            $post->save();

            return response()->json([
                "message" => "Post Updated"
            ], 200);
        } else {
            return response()->json([
                "message" => "Post Not Found Or Does Not Exist"
            ], 404);
        }
    }


    public function destroyPost(Request $request, $id)
    {
        if (Post::where('id', $id)->exists()) {
            $post = Post::find($id);
            $post->delete();

            return response()->json([
                "message" => "Post Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Post Not Found Or Does Not Exist"
            ], 404);
        }
    }
}
