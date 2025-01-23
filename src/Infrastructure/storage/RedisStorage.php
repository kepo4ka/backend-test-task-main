<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\storage;

use Redis;
use RedisException;

final class RedisStorage implements StorageInterface
{
    private Redis $redis;

    public function __construct(
        private readonly string  $host,
        private readonly string  $password,
        private readonly ?string $dbindex = null,
        private readonly ?int    $port = 6379,
    )
    {
    }

    /**
     * @throws StorageException
     */
    public function connect(): void
    {
        try {
            if (!isset($this->redis)) {
                $this->redis = new Redis();
            }

            $isConnected = $this->redis->isConnected();
            if (!$isConnected && $this->redis->ping('Pong')) {
                $isConnected = $this->redis->connect(
                    $this->host,
                    $this->port,
                );
            }

            if ($isConnected) {
                $this->redis->auth($this->password);
                $this->redis->select($this->dbindex);
            }
        } catch (RedisException $e) {
            throw new StorageException('Cannot connect to Redis', $e->getCode(), $e);
        }
    }

    /**
     * @throws RedisException
     */
    public function get(string $key): mixed
    {
        return $this->redis->get($key);
    }

    /**
     * @throws RedisException
     */
    public function set(string $key, string $value, int $ttl = 0): void
    {
        $this->redis->setex($key, $ttl, $value);
    }

    /**
     * @throws RedisException
     */
    public function has(string $key): bool
    {
        return $this->redis->exists($key);
    }
}
