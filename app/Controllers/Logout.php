<?php

namespace App\Controllers;

class Logout extends BaseController {
	
    public function index() {   

        $session = \Config\Services::session($config);
        $session->destroy();			
        return redirect()->to('/login');
		
	
    }

}
