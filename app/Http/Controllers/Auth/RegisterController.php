<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
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
        //
    }

    public function registerManually(Request $request){
        // Validate
        $userCreated = User::firstOrCreate([
            'email' => $request->registerUsername
        ],[
            'email_verified_at' => now(),
            'status' => true,
            'role_id' => 1
        ]);
        
        $userCreated->email = $request->registerUsername;
        $userCreated->born = $request->registerBorn;
        $userCreated->gender = $request->registerGender;
        $userCreated->avatar = $request->registerAvatar->getClientOriginalName();
        $userCreated->password = Hash::make($request->registerPassword);
        $userCreated->name = $request->registerName;
        $userCreated->save();

        // Move file
        $request->registerAvatar->move('images/users',$request->registerAvatar->getClientOriginalName());

        // Create Token 
        $permissions = [];
        foreach ($userCreated->role->permissions as $p){
            $permissions[] = $p->permission_name;
        }
        $token = $userCreated->createToken('dinosaur-database-server-token',$permissions)->plainTextToken;
        $data = [
            'userID' => $userCreated->id,
            'accessToken' => $token
        ];
        return $this->sendResponse($data,'Register Successful');
    }
}
