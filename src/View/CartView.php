<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Doctrine\DBAL\Exception;
use Raketa\BackendTestTask\Domain\Cart\Cart;
use Raketa\BackendTestTask\Domain\Cart\CartItem;
use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

final readonly class CartView
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function toArray(Cart $cart = null): array
    {
        if (null === $cart) {
            return [];
        }

        $data = [
            'uuid'           => $cart->getUuid(),
            'customer'       => [
//                'id'    => $cart->getCustomer()->getId(),
                'email' => $cart->getCustomer()->getEmail(),
                'name'  => implode(' ', [
                    $cart->getCustomer()->getLastName(),
                    $cart->getCustomer()->getFirstName(),
                    $cart->getCustomer()->getMiddleName(),
                ]),
            ],
            'payment_method' => $cart->getPaymentMethod(),
            'total'          => 0,
            'items'          => []
        ];

        $cartItems = $cart->getItems();
        $products = $this->getCartProducts($cartItems);

        if (empty($products)) {
            return $data;
        }

        $products = array_column($products, null, 'uuid');

        /**
         * @var CartItem $item
         */
        foreach ($cartItems as $item) {
            $itemTotal = $item->getTotal();

            /**
             * @var Product $product
             */
            $product = $products[$item->getProductUuid()];

            $data['items'][] = [
                'uuid'     => $item->getUuid(),
                'price'    => $item->getPrice(),
                'total'    => $item_total,
                'quantity' => $item->getQuantity(),
                'product'  => [
                    'id'        => $product->getId(),
                    'uuid'      => $product->getUuid(),
                    'name'      => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price'     => $product->getPrice(),
                ],
            ];
            $data['total'] += $itemTotal;
        }

        return $data;
    }


    /**
     * @throws Exception
     */
    protected function getCartProducts(array $cartItems): array
    {
        $productIds = array_map(
            fn(CartItem $item) => $item->getProductUuid(),
            $cartItems
        );

        return $this->productRepository->getByUuids($productIds);
    }
}
