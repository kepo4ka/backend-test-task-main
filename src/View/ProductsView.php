<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Doctrine\DBAL\Exception;
use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

final readonly class ProductsView
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function toArray(string $category): array
    {
        return array_map(
            fn(Product $product) => [
              //  'id'          => $product->getId(), // надо ли передавать id напрямую из бд?
                'uuid'        => $product->getUuid(),
                'category'    => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail'   => $product->getThumbnail(),
                'price'       => $product->getPrice(),
            ],
            $this->productRepository->getByCategory($category)
        );
    }
}
