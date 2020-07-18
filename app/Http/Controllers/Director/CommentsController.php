<?php

namespace App\Http\Controllers\Director;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;
use Auth;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    
    public function index()
    {
        $director_comments = Comment::
            where('director_comments.director_id', '=', Auth::user()->id)
            ->orderby("director_comments.created_at", 'desc')
            ->paginate(30);
        return CommentResource::collection($director_comments);
    }

    public function search(Request $request)
    {
        $data = request()->validate([
            'searchValue' => 'required',
        ]);

        $comments = Comment::
            where('comment', 'LIKE', '%' . $data['searchValue'] . '%')
            ->orWhere('date', 'LIKE', '%' . $data['searchValue'] . '%')
            ->where('director_comments.director_id', '=', Auth::user()->id)
            ->paginate(30)
            ->appends(request()->query());
        return CommentResource::collection($comments);
    }

    public function show($id)
    {
        $comment = Comment::
            where('id', $id)
            ->where('director_comments.director_id', '=', Auth::user()->id)
            ->firstOrFail();
        return new CommentResource($comment);
    }

    public function store()
    {
        $data = request()->validate([
            'comment' => 'required',
            'date' => 'date',
        ]);
        $data['director_id'] = Auth::user()->id;
        $comment = Comment::create($data);
        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'comment' => 'required',
            'date' => 'date',
        ]);
        $data['director_id'] = Auth::user()->id;
        try {
            Comment::
                whereId($id)
                ->where('director_comments.director_id', '=', Auth::user()->id)
                ->update($data);
            $comment = Comment::
                whereId($id)
                ->where('director_comments.director_id', '=', Auth::user()->id)
                ->get();
            return new CommentResource($comment);
        } catch (\Illuminate\Database\QueryException $e) {
            return response(['error' => $e], 406);
        }
    }

    public function destroy($id)
    {
        $comment = Comment::
            whereId($id)
            ->where('director_comments.director_id', '=', Auth::user()->id)
            ->first();
        if ($comment->delete()) {
            return new CommentResource($comment);
        } else {
            return response(['error' => "we couldn't delete the comment !"], 406);
        }
    }

}
