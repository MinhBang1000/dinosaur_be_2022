<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\UserComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('commentContent')){ 
            if ($request->commentID == 0){
                $commentCreated = Comment::create([
                    'comment_title' => $request->commentTitle,
                    'comment_content' => $request->commentContent,
                    'post_id' => $request->postID,
                    'user_id' => $request->userID,
                    'child_of_comment' => $request->childOfComment, // id of parent comment
                ]);
            }else{
                $commentCreated = Comment::find($request->commentID);
                $commentCreated->comment_content = $request->commentContent;
                $commentCreated->save();
            }
            return $this->sendResponse(new CommentResource($commentCreated),'Store Or Update Comment Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commentCreated = Comment::find($id);
        UserComment::where('comment_id',$id)->delete();
        if (!empty($commentCreated)){
            $commentCreated->delete();
            return $this->sendResponse([],'Delete Comment Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function commentLike($commentID,$userID){
        if (!empty($commentID)){
            $commentLiked = UserComment::create([
                'comment_id' => $commentID,
                'user_id' => $userID,
            ]);
            return $this->sendResponse($commentLiked,'Like Comment Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function commentUnlike($commentID,$userID){
        if (!empty($commentID)){
            $commentLiked = UserComment::where('comment_id',$commentID)->where('user_id',$userID)->first();
            $commentLiked->delete();
            return $this->sendResponse([],'Unlike Comment Successful');
        }
        return $this->sendError('Not Found',[],404);
    }
}
