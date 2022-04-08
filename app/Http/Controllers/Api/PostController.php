<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostImageResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostDinosaur;
use App\Models\PostImage;
use App\Models\UserComment;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(PostResource::collection(Post::orderBy('created_at','desc')->get()),'Index Post Successful');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postCreated = Post::create([
            'post_title' => $request->postTitle,
            'post_content' => $request->postContent,
            'post_avatar' => $request->hasFile('postAvatar')?$request->postAvatar->getClientOriginalName():null,
            'user_id' => $request->userID,
        ]);
        if ($request->hasFile('postAvatar')){
            $request->postAvatar->move('images/avatars',$request->postAvatar->getClientOriginalName());    
        }
        foreach ($request->postRelate as $pd){
            $pdCreated = PostDinosaur::create([
                'post_id' => $postCreated->id,
                'dinosaur_id' => $pd
            ]);
        }   
        return $this->sendResponse(new PostResource($postCreated),'Store Post Successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $post = Post::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        return $this->sendResponse(new PostResource($post),'Show Post Successful');
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
        try{
            $post = Post::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        $post->post_title = !is_null($request->postTitle)?$request->postTitle:$post->post_title;
        $post->post_content = !is_null($request->postContent)?$request->postContent:$post->post_content;
        $post->post_avatar = !is_null($request->postAvatar)?$request->postAvatar->getClientOriginalName():$post->post_avatar;
        if ($request->hasFile('postAvatar')){
            $request->postAvatar->move('images/avatars',$request->postAvatar->getClientOriginalName());    
        }
        $postDinosaurs = PostDinosaur::where('post_id',$id)->get();
        if (!is_null($postDinosaurs)){
            foreach ($postDinosaurs as $pdo) {
                $pdo->delete();
            }
        }
        if (!is_null($request->postRelate)){
            foreach ($request->postRelate as $pd){
                PostDinosaur::create([
                    'post_id' => $id,
                    'dinosaur_id' => $pd
                ]);
            }
        }
        // Cho duyệt lại
        $post->post_decision = 0;
        $post->save();
        return $this->sendResponse(new PostResource($post),'Update Post Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postDinosaurs = PostDinosaur::where('post_id',$id)->get();
        if (!is_null($postDinosaurs)){
            foreach ($postDinosaurs as $pdo){
                $pdo->delete();
            }
        }
        $postUsers = UserPost::where('post_id',$id)->get();
        if (!is_null($postUsers)){
            foreach ($postUsers as $pdu){
                $pdu->delete();
            }
        }
        $postComments = Comment::where('post_id',$id)->get();
        if (!is_null($postComments)){
            foreach ($postComments as $pdc){
                $userComment = UserComment::where('comment_id',$pdc->id)->get();
                if (!is_null($userComment)){
                    foreach ($userComment as $uc){
                        $uc->delete();
                    }
                }
                $pdc->delete();
            }
        }
        try{
            $post = Post::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        $post->delete();
        return $this->sendResponse([],'Destroy Post Successful');
    }


    public function postImageAjax(Request $request){
        if ($request->hasFile('postImage')){
            $file = $request->postImage;
            $file->move('images/posts',$file->getClientOriginalName());
            $fileCreated = PostImage::create([
                'post_image_path' => $file->getClientOriginalName(),
            ]);
            return $this->sendResponse(new PostImageResource($fileCreated),'Store File Successful');
        }
        return $this->sendError('Not Found',[],404);
    }


    public function postSearchSomeThing(Request $request){
        if ($request->has('postSearch')){
            $key = $request->postSearch;
            $postList = Post::where('post_title','like','%'.$key.'%')->orderBy('created_at','desc')->get();
            return $this->sendResponse(PostResource::collection($postList),'Search Post Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function postSortA(Request $request){
        if ($request->has('postSortA')){
            $postList = $request->postSortA==0?Post::orderBy('post_title','asc')->get():Post::orderBy('post_title','desc')->get();
            return $this->sendResponse(PostResource::collection($postList),'Sort A Post Successful');            
        }
        return $this->sendError('Not Found',[],404);
    }

    public function postSortB(Request $request){
        if ($request->has('postSortB')){
            $postList = $request->postSortB==0?Post::orderBy('created_at','desc')->get():Post::orderBy('created_at','asc')->get();
            return $this->sendResponse(PostResource::collection($postList),'Sort B Post Successful');            
        }
        return $this->sendError('Not Found',[],404);
    }

    public function postLike($postID,$userID){
        if (!empty($postID)){
            $postLiked = UserPost::create([
                'post_id' => $postID,
                'user_id' => $userID,
            ]);
            return $this->sendResponse($postLiked,'Like Post Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function postUnlike($postID,$userID){
        if (!empty($postID)){
            $postLiked = UserPost::where('post_id',$postID)->where('user_id',$userID)->first();
            $postLiked->delete();
            return $this->sendResponse([],'Unlike Post Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function decision($id){
        try{
            $post = Post::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        $post->post_decision = 1;
        $post->save();
        return $this->sendResponse([],'Decision Post Successful');
    }
}
