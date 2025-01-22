<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Customer;

final class Cart
{
    /**
     * @param CartItem[] $items Массив элементов корзины.
     */
    public function __construct(
        readonly private string $uuid,
        readonly private Customer $customer,
        readonly private string $paymentMethod,
        private array $items,
    ) {
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
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function calculateItemsTotalPrice(): float {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getPrice();
        }
    }

}
