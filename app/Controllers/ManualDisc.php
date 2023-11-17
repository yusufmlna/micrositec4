<?php

namespace App\Controllers;

class ManualDisc extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Manual Disc';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('manual_disc');

            return view('base_view', $data);
        }
    }

    public function submit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns']) && isset($_POST['discount_amount']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {

                $ocModel = new \App\Models\OcModel();
                $isbnsArray = explode(',', $_POST['isbns']);

                foreach ($isbnsArray as $i) {
                    $ocModel->manualDiscount($i, $_POST['discount_amount'], $_POST['start_date'], $_POST['end_date']);
                }

                return redirect()->to('manual_disc?msg=Discount Created Successfully&type=success');
            } else {
                return redirect()->to('manual_disc');
            }
        }
    }
}
