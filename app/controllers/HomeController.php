<?php

require_once __DIR__ . '/../models/ProductModel.php';

class HomeController extends Controller
{
    public function index()
    {
        $productModel = new ProductModel();

        // La portada destaca una selección acotada de productos de la semana.
        $featuredProducts = $productModel->obtenerProductosDestacadosSemana(4);
        $promoProducts = $productModel->obtenerProductosEnPromocion(4);

        $this->view('home/index', [
            'featuredProducts' => $featuredProducts,
            'promoProducts' => $promoProducts
        ]);
    }
}