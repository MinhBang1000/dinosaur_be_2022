<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    protected function validateProvider($provider){
        if (!in_array($provider,['github','facebook','google'])){
            return $this->sendError('Invalid Third Party',['error' => 'Please login using facebook or github or google'],422);
        }
    }
    
    /**
     * Be called when validateProvider invalid
     * @return error of validateProvider function
     * @param $provider that is typed-hint string in url of route
     */
    public function redirectToProvider($provider){
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)){
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
        // call to redirect path in services file in config
        // continue call handleProviderCallback
    }

    /**
     * Be called after user accepted for third party
     * @return new user
     */
    public function handleProviderCallback($provider){
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)){
            return $validated;
            // return above error
        }

        try{
            $user = Socialite::driver($provider)->stateless()->user();
            // get user in Third Party not User in this app
            // stateless to disable session for API app
        }catch (ClientException $exception){
            return $this->sendError('Client Exception',['error' => 'Invalid credentials provided','exception' => $exception],422);
        }
        //finding user by email
        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
                'role_id' => 1
            ]
        );
        // auto match user_id for providers
        // one user can have many providers that is google, github or facebook
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId()    
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        $permissions = [];
        foreach ($userCreated->role->permissions as $p){
            $permissions[] = $p->permission_name;
        }
        $token = $userCreated->createToken('dinosaur-database-server-token',$permissions)->plainTextToken;
        $data = [
            'user_id' => $userCreated->id,
            'access-token' => $token
        ];
        return $this->sendResponse($data,'Login Successful');
    }
}
