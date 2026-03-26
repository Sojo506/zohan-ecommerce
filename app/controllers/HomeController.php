<?php

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';

class HomeController extends Controller
{
    public function index()
    {
        $productModel = new ProductModel();

        // La portada destaca una seleccion acotada de productos de la semana.
        $featuredProducts = $productModel->obtenerProductosDestacadosSemana(4);
        $promoProducts = $productModel->obtenerProductosEnPromocion(4);
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
