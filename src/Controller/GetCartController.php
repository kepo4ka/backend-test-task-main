<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Utils\Http\JsonResponse;
use Raketa\BackendTestTask\View\CartView;

readonly class GetCartController
{
    public function __construct(
        private CartView    $cartView,
        private CartManager $cartManager
    )
    {
    }

    /**
     * @return ResponseInterface
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function execute(RequestInterface $request): ResponseInterface
    {
        return (new JsonResponse())->create([
            'status' => 'success',
            'cart'   => $this->cartView->toArray($this->cartManager->get())
        ]);
    }
}
