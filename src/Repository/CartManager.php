<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart\Cart;
use Raketa\BackendTestTask\Domain\Cart\CartItem;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Infrastructure\storage\StorageException;
use Raketa\BackendTestTask\Infrastructure\storage\StorageInterface;

final readonly class CartManager
{
    private const STORAGE_PREFIX = 'customer_cart';
    private string $sessionId;
    private string $storage_key;

    public function __construct(
        private StorageInterface $storage,
        private LoggerInterface  $logger,
        private string           $prefix = self::STORAGE_PREFIX
    )
    {
        $this->sessionId = session_id(); // если добавится мобильное приложение/API, то будет грустно
        $this->storage_key = $this->buildStorageKey();
    }

    public function save(Cart $cart): void
    {
        try {
            $this->storage->set(
                $this->storage_key,
                serialize($cart)
            );
        } catch (Exception $e) {
            $this->logger->error('Failed to save cart', [
                'error'     => $e->getMessage(),
                'sessionId' => $this->sessionId
            ]);
        }
    }

    /**
     * @throws Exception
     */
    public function get(): Cart
    {
        try {
            $cart_data = $this->storage->get($this->storage_key);
            if (false === $cart_data) {
                return $this->create();
            }

            return unserialize($cart_data);
        } catch (Exception $e) {
            $this->logger->error('Failed to get cart', [
                'error'     => $e->getMessage(),
                'sessionId' => $this->sessionId
            ]);
        }
        return $this->create();
    }

    public function create(): Cart
    {
        // Заглушка (допустим, что cartManager как-то знает текущего клиента)
        $customer = new Customer(
            123,
            'Иван',
            'Фёдоров',
            'Иванович',
            'ivan@example.com',
        );

        // Заглушка
        $paymentMethod = 'tinkoff';

        return new Cart(
            Cart::generateUuid(),
            $customer,
            $paymentMethod,
            []
        );
    }

    private function buildStorageKey(): string
    {
        return "{$this->prefix}:{$this->sessionId}";
    }
}
