<?php

namespace App\Controllers;

use PHPExcel;
use PHPExcel_IOFactory;

class ModuleProduct extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Products';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_product');

            return view('base_view', $data);
        }
    }

    public function submit()
    {

        if (isset($_POST)) {

            $ocModel = new \App\Models\OcModel();

            $module_id = $_POST['module_id'];
            $isbns = $_POST['isbns'];

            $isbns_array = explode(",", $isbns);
            $products = [];

            foreach ($isbns_array as $i) {

                $product_id = $ocModel->getProductId($i);

                if ($product_id) {
                    $products[] = $product_id->product_id;
                }
            }

            $journal_module = $ocModel->getJournalModule($module_id);

            if (!$journal_module) {
                return redirect()->to('/');
            }

            $module_data = json_decode($journal_module->module_data);
            $module_data->items[0]->filter->products = $products;

            $new_module_data = json_encode($module_data);

            if (count($products) > 0) {
                $ocModel->updateJournalModuleData($module_id, $new_module_data);
            }

            if ($module_id == "296") {
                // New Release Module
                $this->labelUpdater("29", $products);
            }

            $ocModel->categoryUpdate($module_id, $products);

            return redirect()->to('/module_products?msg=Module Updated Successfully&type=success');
        } else {
            return redirect()->to('/');
        }
    }

    private function labelUpdater($module_id, $products)
    {

        $ocModel = new \App\Models\OcModel();
        $journal_module = $ocModel->getJournalModule($module_id);
        $module_data = json_decode($journal_module->module_data);
        $module_data->general->filter->products = $products;
        $new_module_data = json_encode($module_data);
        if (count($products) > 0) {
            $ocModel->updateJournalModuleData($module_id, $new_module_data);
        }
    }
}
