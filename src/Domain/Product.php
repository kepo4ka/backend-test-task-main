<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

final readonly class Product
{
    public function __construct(
        private int     $id,
        private string  $uuid,
        private bool    $isActive,
        private string  $category,
        private string  $name,
        private float   $price,
        private ?string $description = null,
        private ?string $thumbnail = null,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function getPrice(): float
    {
        return round($this->price, 2);
    }
}
