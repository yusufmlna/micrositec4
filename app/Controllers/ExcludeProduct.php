<?php

namespace App\Controllers;

class ExcludeProduct extends BaseController
{

    public function index()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            $productModel = new \App\Models\ProductsModel();

            if(isset($_GET['action']) && isset($_GET['isbn'])){

                if($_GET['action'] == 'delete'){

                    $productModel->deleteExcludeProduct($_GET['isbn']);
                    return redirect()->to('exclude_product?msg=Product Deleted Successfully&type=success');

                }

            }

            $page = 'Exclude Product';

            $excludedProducts = $productModel->getExcludedProduct();

            if($excludedProducts){
                $vData['products'] = $excludedProducts;
            } else {
                $vData['products'] = null;
            }

            $data['pageTitle'] = $page . ' | eCommerce Microsite';
            $data['activeNav'] = $page;
            $data['content'] = view('exclude_product', $vData);

            return view('base_view', $data);
        }
    }

    public function submit()
    {

        $session = \Config\Services::session($config);

        if (!$session->has('user_id')) {

            return redirect()->to('/login?msg=Unauthorized Access');
        } else {

            if (isset($_POST['isbn']) && isset($_POST['stock']) && isset($_POST['stock_status_id'])) {

                $productModel = new \App\Models\ProductsModel();

                $isbn = $_POST['isbn'];
                $stock = $_POST['stock'];
                $stockStatusId = $_POST['stock_status_id'];
                
                $productModel->createExcludeProduct($isbn, $stock, $stockStatusId);
                
                return redirect()->to('exclude_product?msg=Product Added Successfully&type=success');
            } else {
                return redirect()->to('exclude_product');
            }
        }
    }
}
