<?php

namespace Raketa\BackendTestTask\Controller;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Cart\CartItem;
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

    /**
     * @throws Exception
     */
    public function execute(RequestInterface $request): ResponseInterface
    {
        // Считаю, что валидный json
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        if (empty($rawRequest['productUuid']) || empty($rawRequest['quantity']) || (intval($rawRequest['quantity']) < 1)) {
            return (new JsonResponse())->create([
                'status' => 'error',
                'error'  => 'Invalid request',
            ], 400);
        }

        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        $cart = $this->cartManager->get();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        return (new JsonResponse())->create([
            'status' => 'success',
            'cart'   => $this->cartView->toArray($cart)
        ]);
    }
}
