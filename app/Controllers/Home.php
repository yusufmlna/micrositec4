<?php

namespace App\Controllers;

class Home extends BaseController {

	public function index() {

		$session = \Config\Services::session($config);

		if(!$session->has('user_id')) {
			
			return redirect()->to('/login?msg=Unauthorized Access');
		
		} else {	

			$page = 'Dashboard';

			$data['pageTitle'] = $page . ' | eCommerce Microsite';
			$data['activeNav'] = $page;
			$data['content'] = view('home');

			return view('base_view', $data);

		}

	}

}
