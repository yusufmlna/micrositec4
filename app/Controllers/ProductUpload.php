<?php

namespace App\Controllers;

use PHPExcel;
use PHPExcel_IOFactory;

class ProductUpload extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Products Upload';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('product_upload');

            return view('base_view', $data);
        }
    }

    public function upload()
    {

        $file = fopen($_FILES['products_file']['tmp_name'], "r");

        if ($file) {

            $excelReader  = new PHPExcel();
            $objPHPExcel = PHPExcel_IOFactory::load($_FILES['products_file']['tmp_name']);
            $sheet    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            $productsModel = new \App\Models\ProductsModel();

            if (count($sheet) < 1) {
                return redirect()->to('/products_upload?msg=Invalid File Content&type=error');
            }

            if (count($sheet) > 501) {
                return redirect()->to('/products_upload?msg=File Row is More Than 500&type=error');
            }

            foreach ($sheet as $idx => $data) {
                //skip index 1 karena title excel
                if ($idx == 1) {
                    continue;
                }

                $productsModel->uploadProduct($data);
            }

            fclose($file);

            return redirect()->to('/products_upload?msg=File Uploaded Successfully&type=success');
        } else {

            return redirect()->to('/products_upload?msg=System Error&type=error');
        }
    }
}
