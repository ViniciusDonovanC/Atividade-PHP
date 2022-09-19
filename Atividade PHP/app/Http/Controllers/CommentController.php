<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function indexComment(Request $request, $id)
    {
        return Comment::where('fk_postagem_id', $id)->get();
    }


    public function storeComment(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'descricao' => ['required', 'max:200'],
            'usuario' => ['required', 'max:20'],
            
        ]);

        if (!$validated->fails()) {

            $comment = new Comment;
            $comment->descricao = $request->descricao;
            $comment->usuario = $request->usuario;
            $comment->fk_postagem_id = $id;
            $comment->save();

            return response()->json([
                "message" => "Comment Created"
            ], 201);
        }

        return response()->json([
            "message" => $validated->errors()->all()
        ], 500);
    }


    public function showComment(Request $request, $id, $id_comments)
    {
        if (Comment::where('id', $id_comments)->exists()) {
            $comment = Comment::find($id_comments);

            if (!($comment->fk_postagem_id == $id)) {
                return response()->json([
                    "message" => "Comment Not Found On Post"
                ], 404);
            }

            return response($comment, 200);
        } else {
            return response()->json([
                "message" => "Comment Not Found Or Does Not Exist"
            ], 404);
        }
    }


    public function editComment(Request $request, $id, $id_comments)
    {
        if (Comment::where('id', $id_comments)->exists()) {

            $comment = Comment::find($id_comments);

            if (!($comment->fk_postagem_id == $id)) {
                return response()->json([
                    "message" => "Comment Not Found Or Does Not Exist On Post"
                ], 404);
            }

            $comment->descricao = ($request->has('descricao')) ? $request->descricao : $comment->descricao;
            $comment->save();

            return response()->json([
                "message" => "Comment Updated"
            ], 200);
        } else {
            return response()->json([
                "message" => "Comment Not Found Or Does Not Exist"
            ], 404);
        }
    }



    public function destroyComment(Request $request, $id, $id_comments)
    {
        if (Comment::where('id', $id_comments)->exists()) {

            $comments = Comment::find($id_comments);

            if (!($comments->fk_postagem_id == $id)) {
                return response()->json([
                    "message" => "Comment Not Found Or Does Not Exist On Post"
                ], 404);
            }

            $comments->delete();

            return response()->json([
                "message" => "Comment Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Comment Not Found Or Does Not Exist"
            ], 404);
        }
    }
}
