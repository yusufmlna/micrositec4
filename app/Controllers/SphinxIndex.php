<?php

namespace App\Controllers;

class SphinxIndex extends BaseController {

	public function index() {

		$session = \Config\Services::session($config);

		if(!$session->has('user_id')) {
			
			return redirect()->to('/login?msg=Unauthorized Access');
		
		} else {	

			print_r(shell_exec('sudo -S /var/www/bnbsite/scripts/./sphinx_indexer.sh'));
			return redirect()->to('/?msg=Indexing Success');

		}

	}

}
