<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Utils\Http\JsonResponse;
use Raketa\BackendTestTask\View\ProductsView;

readonly class GetProductsController
{
    public function __construct(
        private ProductsView $productsVew
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

        if (empty($rawRequest['category'])) {
            return (new JsonResponse())->create([
                'status'  => 'error',
                'message' => 'Category is required'
            ], 400);
        }

        return (new JsonResponse())->create([
            'status' => 'success',
            'data'   => $this->productsVew->toArray($rawRequest['category'])
        ]);
    }
}
