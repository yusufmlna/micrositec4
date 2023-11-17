<?php

namespace App\Controllers;

class ImageUpdate extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Image Update';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('image_update');

            return view('base_view', $data);
        }
    }

    public function submit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbn']) && isset($_POST['url'])) {

                $url = $_POST['url'];
                $isbn = $_POST['isbn'];

                if (!file_exists('/var/www/bnbsite/htdocs/image/products/' . substr($isbn, -3))) {
                    mkdir('/var/www/bnbsite/htdocs/image/products/' . substr($isbn, -3));
                }

                $img = '/var/www/bnbsite/htdocs/image/products/' . substr($isbn, -3) . '/' . $isbn . '.jpg';
                file_put_contents($img, file_get_contents($url));
                return redirect()->to('image_update?msg=Image Updated Successfully&type=success');
            } else {
                return redirect()->to('image_update');
            }
        }
    }
}
