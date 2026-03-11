<?php

require_once __DIR__ . '/../models/ProductModel.php';

class HomeController extends Controller
{
    public function index()
    {
        $productModel = new ProductModel();
        $featuredProducts = $productModel->obtenerProductosDestacadosSemana(4);

        $this->view('home/index', [
            'featuredProducts' => $featuredProducts
        ]);
    }
}
