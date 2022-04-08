<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
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
        //
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
            $user = User::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        return $this->sendResponse(new UserResource($user),'Show User Successful');
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
        return $request;
        try {
            $user = User::findOrFail($id);
        } catch (NotFoundHttpException $exception) {
            return $this->sendError('Model Not Found', $exception, 404);
        }
        $user->email = $request->profileUsername;
        $user->name = $request->profileName;
        $user->born = $request->profileBorn;
        $user->gender = $request->profileGender;
        $user->avatar = is_null($request->profileAvatar)?$user->avatar:$request->profileAvatar->getClientOriginalName();
        
        if (!is_null($request->profileAvatar)){
            $request->profileAvatar->move("images/users",$request->profileAvatar->getClientOriginalName());
        }

        $user->save();

        return $this->sendResponse($user,'Update User Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
