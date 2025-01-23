<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Cart;

final class CartItem
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $productUuid,
        private readonly float  $price,
        private int             $quantity,
    )
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getPrice(): float
    {
        return round($this->price, 2);
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): float
    {
        return round($this->price * $this->quantity, 2);
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
