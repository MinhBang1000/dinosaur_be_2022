<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * @return JSON 
     * @param $id of user need logout
     */
    public function logoutHandle($id){
        PersonalAccessToken::where('tokenable_id','=',$id)->delete();
        return $this->sendResponse([],'Logout Successful');
    }
}
