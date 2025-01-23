<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Exception;
use Raketa\BackendTestTask\Domain\Product;

final class ProductRepository extends Repository
{
    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function getByUuid(string $uuid): ?Product
    {
        $rows = $this->getByUuids([$uuid]);
        if (empty($rows)) {
            return null;
        }
        return reset($rows);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getByUuids(array $uuids): array
    {
        return array_map(
            fn(array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                "SELECT * FROM products WHERE is_active = 1 AND uuid IN (:uuids)",
                [
                    'uuids' => $uuids,
                ],
            )
        );
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getByCategory(string $category): array
    {
        return array_map(
            fn(array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                "SELECT id FROM products WHERE is_active = 1 AND category = :category",
                [
                    'category' => $category,
                ]
            )
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
