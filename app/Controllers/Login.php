<?php

namespace App\Controllers;

class Login extends BaseController {
	
    public function index() {   

        $session = \Config\Services::session($config);
        $view = \Config\Services::renderer();

		if($session->has('user_id')) {
			
			return redirect()->to('/');
		
		} else {

            $data['pageTitle'] = 'Login | eCommerce Microsite';
			
            return view('login', $data);

		}
	
    }

    public function submit() {

        $request = service('request');
        $session = \Config\Services::session($config);

        if( (!empty($request->getPost('email'))) && (!empty($request->getPost('password'))) ){

            $usersModel = new \App\Models\UsersModel();
            $email = $request->getPost('email');
            $password = $request->getPost('password');

            $userData = $usersModel->userLogin($email, $password);

            if(!$userData){

                return redirect()->to('/login?msg=Invalid Credentials&type=error');

            } else {

                $sessionUserData = [
                    "user_id" => $userData->user_id,
                    "username" => $userData->username,
                    "fullname" => $userData->fullname,
                    "email" => $userData->email,
                    "role" => $userData->role
                ];

                $session->set($sessionUserData);

                return redirect()->to('/?msg=Welcome ' . $userData->fullname . '!');

            }

        } else {

            return redirect()->to('/login?msg=Unauthorized Access');

        }

    }

}
