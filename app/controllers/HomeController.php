<?php

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';

class HomeController extends Controller
{
    public function index()
    {
        $productModel = new ProductModel();
        $featuredProducts = $productModel->obtenerProductosDestacadosSemana(8);
        $promoProducts = $productModel->obtenerProductosEnPromocion(8);
        $repository = new ProductRepository($productModel);
        $cart = $repository->sanitizeCart($_SESSION['cart'] ?? []);
        if (empty($cart) && isset($_SESSION['user']['id_cuenta'])) {
            $cart = $repository->fetchCartForAccount((int)$_SESSION['user']['id_cuenta']);
        }
        $_SESSION['cart'] = $cart;
        $cartCount = $repository->countCart($cart);

        $this->view('home/index', [
            'featuredProducts' => $featuredProducts,
            'promoProducts' => $promoProducts,
            'cartCount' => $cartCount
        ]);
    }
}
