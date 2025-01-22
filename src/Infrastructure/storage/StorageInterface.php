<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\storage;

interface StorageInterface
{
    public function connect(): void;

    public function get(string $key);

    public function set(string $key, string $value, int $ttl = 0): void;

     public function has(string $key): bool;
}
