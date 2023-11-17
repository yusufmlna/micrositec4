<?php

namespace App\Controllers;

class ModuleSetting extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting');

            return view('base_view', $data);
        }
    }

    public function topPicksModule()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting_top_picks');

            return view('base_view', $data);
        }
    }

    public function topPicksModuleSubmit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns'])) {

                $ocModel = new \App\Models\OcModel();

                $module_id = "298";
                $category_id = "8025";
                $isbns_array = explode(",", $_POST['isbns']);
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

                $ocModel->categoryUpdate($category_id, $products);

                return redirect()->to('/module_setting?msg=Module Updated Successfully&type=success');
            } else {
                redirect()->to('/');
            }
        }
    }

    public function newReleaseModule()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting_new_release');

            return view('base_view', $data);
        }
    }

    public function newReleaseModuleSubmit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns'])) {

                $ocModel = new \App\Models\OcModel();

                $module_id = "296";
                $category_id = "7977";
                $isbns_array = explode(",", $_POST['isbns']);
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
                    $this->labelUpdater("29", $products);
                }

                $ocModel->categoryUpdate($category_id, $products);

                return redirect()->to('/module_setting?msg=Module Updated Successfully&type=success');
            } else {
                redirect()->to('/');
            }
        }
    }

    public function flashSaleModule()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting_flash_sale');

            return view('base_view', $data);
        }
    }

    public function flashSaleModuleSubmit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns'])) {

                $ocModel = new \App\Models\OcModel();

                $module_id = "288";
                $category_id = "8023";
                $isbns_array = explode(",", $_POST['isbns']);
                $products = [];
                $end_date = $_POST['end_date'] . ' 23:59:59';

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

                $ocModel->categoryUpdate($category_id, $products);

                $flashsale_countdown = $ocModel->getJournalModule('295');
                $flashsale_data = json_decode($flashsale_countdown->module_data);
                $flashsale_data->general->date = $end_date;
                $new_flashsale_data = json_encode($flashsale_data);

                $ocModel->updateJournalModuleData('295', $new_flashsale_data);

                return redirect()->to('/module_setting?msg=Module Updated Successfully&type=success');
            } else {
                redirect()->to('/');
            }
        }
    }

    public function bestsellerBooksModule()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting_bestseller_books');

            return view('base_view', $data);
        }
    }

    public function bestsellerBooksModuleSubmit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns'])) {

                $ocModel = new \App\Models\OcModel();

                $module_id = "302";
                $category_id = "8024";
                $isbns_array = explode(",", $_POST['isbns']);
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

                $ocModel->categoryUpdate($category_id, $products);

                return redirect()->to('/module_setting?msg=Module Updated Successfully&type=success');
            } else {
                redirect()->to('/');
            }
        }
    }

    public function bestsellerToysModule()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting_bestseller_toys');

            return view('base_view', $data);
        }
    }

    public function bestsellerToysModuleSubmit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns'])) {

                $ocModel = new \App\Models\OcModel();

                $module_id = "302";
                $category_id = "8026";
                $isbns_array = explode(",", $_POST['isbns']);
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
                $module_data->items[1]->filter->products = $products;

                $new_module_data = json_encode($module_data);

                if (count($products) > 0) {
                    $ocModel->updateJournalModuleData($module_id, $new_module_data);
                }

                $ocModel->categoryUpdate($category_id, $products);

                return redirect()->to('/module_setting?msg=Module Updated Successfully&type=success');
            } else {
                redirect()->to('/');
            }
        }
    }

    public function lpitModule()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $page = 'Module Setting';

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('module_setting_lpit');

            return view('base_view', $data);
        }
    }

    public function lpitModuleSubmit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbns'])) {

                $ocModel = new \App\Models\OcModel();

                $module_id = "319";
                $category_id = "8057";
                $isbns_array = explode(",", $_POST['isbns']);
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

                $ocModel->categoryUpdate($category_id, $products);

                return redirect()->to('/module_setting?msg=Module Updated Successfully&type=success');
            } else {
                redirect()->to('/');
            }
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
