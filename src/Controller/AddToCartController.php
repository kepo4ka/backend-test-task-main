<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Entity\CartItem;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\Utils\Http\JsonResponse;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView          $cartView,
        private CartManager       $cartManager,
    )
    {
    }

    public function execute(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        // Проверка на ошибку декодирования JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $response = new JsonResponse();
            $response->create([
                'status'  => 'error',
                'message' => 'Invalid JSON format'
            ], 400);
            return $response;
        }

        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        $cart = $this->cartManager->getCart();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        $response = new JsonResponse();
        $response->create([
            'status' => 'success',
            'cart'   => $this->cartView->toArray($cart)
        ]);

        return $response;
    }
}
