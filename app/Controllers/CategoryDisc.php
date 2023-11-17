<?php

namespace App\Controllers;

class CategoryDisc extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Category Disc';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('category_disc');

            return view('base_view', $data);
        }
    }

    public function submit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['category_id']) && isset($_POST['discount_amount']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {

                $ocModel = new \App\Models\OcModel();

                $ocModel->categoryDiscount($_POST['category_id'], $_POST['discount_amount'], $_POST['start_date'], $_POST['end_date']);

                return redirect()->to('category_disc?msg=Discount Created Successfully&type=success');
            } else {
                return redirect()->to('category_disc');
            }
        }
    }
}
