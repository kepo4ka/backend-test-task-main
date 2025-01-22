<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Cart;

use Raketa\BackendTestTask\Domain\Customer;
use Ramsey\Uuid\Uuid;

final class Cart
{
    /**
     * @param CartItem[] $items Массив элементов корзины.
     */
    public function __construct(
        readonly private string   $uuid,
        readonly private Customer $customer,
        readonly private string   $paymentMethod,
        private array             $items,
    )
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return array_filter($this->items, fn(CartItem $item) => $item->getQuantity() > 0);
    }

    public function addItem(CartItem $item): void
    {
        foreach ($this->items as $existingItem) {
            if ($existingItem->getUuid() === $item->getUuid()) {
                $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());
                return;
            }
        }

        // Если элемента нет в корзине, добавляем его
        $this->items[] = $item;
    }

    public function removeItem(CartItem $item): void
    {
        $this->items = array_filter(
            $this->items,
            fn(CartItem $cartItem) => $cartItem->getUuid() !== $item->getUuid()
        );
    }

    public function updateItemQuantity(CartItem $updating_item, int $newQuantity): void
    {
        foreach ($this->items as $item) {
            if ($item->getUuid() === $updating_item->getUuid()) {
                $item->setQuantity($newQuantity);
                return;
            }
        }

        if ($newQuantity > 0) {
            $this->addItem($updating_item);
        }
        $this->items = $this->getItems();
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function calculateTotalPrice(): float
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getPrice();
        }
        return $total;
    }

    public static function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
