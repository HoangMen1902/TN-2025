<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('usermodule::auth.login'); 
    }
    public function showRegisterForm()
    {
        return view('usermodule::auth.register'); 
    }
    public function showForgotPasswordForm()
    {
        return view('usermodule::auth.forgot-password'); 
    }
    public function showChangeForgotPasswordForm()
    {
        return view('usermodule::auth.change-forgot-password'); 
    }
    public function showChangePasswordForm()
    {
        return view('usermodule::auth.change-password'); 
    }
    public function showProfileInfomation(){
        return view('usermodule::profile.infomation');
    }

    public function showAddressInfomation(){
        return view('usermodule::profile.address');
    }
}
