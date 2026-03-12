<?php

require_once __DIR__ . '/ProductRepository.php';

class CartRepository
{
    private ProductRepository $products;

    public function __construct(?ProductRepository $products = null)
    {
        $this->products = $products ?? new ProductRepository();
    }

    public function syncSessionCart(): array
    {
        $cart = $this->products->sanitizeCart($_SESSION['cart'] ?? []);

        if (empty($cart) && isset($_SESSION['user']['id_cuenta'])) {
            $cart = $this->products->fetchCartForAccount((int)$_SESSION['user']['id_cuenta']);
        }

        $this->setCart($cart, false);

        return $cart;
    }

    public function getCart(): array
    {
        return $this->products->sanitizeCart($_SESSION['cart'] ?? []);
    }

    public function setCart(array $cart, bool $persist = true): void
    {
        $cleanCart = $this->products->sanitizeCart($cart);
        $_SESSION['cart'] = $cleanCart;

        if ($persist) {
            $this->persistCartForLoggedUser($cleanCart);
        }
    }

    public function clear(): void
    {
        $this->setCart([]);
    }

    public function buildSummary(): array
    {
        $summary = $this->products->buildCartSummary($this->getCart());
        $this->setCart($summary['cart']);

        return $summary;
    }

    public function countCurrentCart(): int
    {
        return $this->products->countCart($this->getCart());
    }

    public function addProduct(int $productId, int $quantity): array
    {
        $quantity = max(1, $quantity);
        $product = $this->products->fetchProduct($productId);

        if (!$product) {
            return [
                'success' => false,
                'message' => 'No se pudo agregar: producto invalido.',
                'redirect' => App::url('/products'),
            ];
        }

        $stock = $this->products->fetchProductStock($productId);
        if ($stock <= 0) {
            return [
                'success' => false,
                'message' => 'Producto sin existencias.',
            ];
        }

        $cart = $this->getCart();
        $actual = (int)($cart[$productId] ?? 0);
        $maxAgregar = $stock - $actual;

        if ($maxAgregar <= 0) {
            return [
                'success' => false,
                'message' => 'No hay mas existencias disponibles.',
            ];
        }

        $solicitado = $quantity;
        $quantity = min($quantity, $maxAgregar);
        $cart[$productId] = $actual + $quantity;
        $this->setCart($cart);

        return [
            'success' => true,
            'message' => $quantity < $solicitado
                ? 'Cantidad ajustada a existencias.'
                : 'Producto agregado al carrito.',
        ];
    }

    public function updateProduct(int $productId, string $action = '', ?int $quantity = null): array
    {
        if ($productId <= 0) {
            return [
                'success' => false,
                'message' => 'Producto invalido.',
            ];
        }

        $cart = $this->getCart();
        if (!isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'El producto no esta en el carrito.',
            ];
        }

        $stock = $this->products->fetchProductStock($productId);
        if ($stock <= 0) {
            unset($cart[$productId]);
            $this->setCart($cart);

            return [
                'success' => false,
                'message' => 'Producto sin existencias.',
            ];
        }

        $actual = (int)$cart[$productId];
        $adjusted = false;

        if ($action === 'sumar') {
            if ($actual >= $stock) {
                $this->setCart($cart);

                return [
                    'success' => false,
                    'message' => 'No hay mas existencias disponibles.',
                ];
            }

            $newQuantity = $actual + 1;
        } elseif ($action === 'restar') {
            $newQuantity = $actual - 1;
        } else {
            $newQuantity = (int)($quantity ?? $actual);
            if ($newQuantity > $stock) {
                $newQuantity = $stock;
                $adjusted = true;
            }
        }

        if ($newQuantity <= 0) {
            unset($cart[$productId]);
            $message = 'Producto eliminado del carrito.';
        } else {
            $cart[$productId] = $newQuantity;
            $message = $adjusted
                ? 'Cantidad ajustada a existencias.'
                : 'Cantidad actualizada en el carrito.';
        }

        $this->setCart($cart);

        return [
            'success' => true,
            'message' => $message,
        ];
    }

    public function removeProduct(int $productId): array
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->setCart($cart);

            return [
                'success' => true,
                'message' => 'Producto eliminado del carrito.',
            ];
        }

        return [
            'success' => true,
            'message' => 'El carrito ya estaba actualizado.',
        ];
    }

    private function persistCartForLoggedUser(array $cart): void
    {
        if (!isset($_SESSION['user']['id_cuenta'])) {
            return;
        }

        $idCuenta = (int)$_SESSION['user']['id_cuenta'];
        if ($idCuenta <= 0) {
            return;
        }

        try {
            $this->products->saveCartForAccount($idCuenta, $cart);
        } catch (PDOException $e) {
            // Evitar romper la navegacion si la persistencia falla.
        }
    }
}
