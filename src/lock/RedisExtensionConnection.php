<?php

namespace taobig\helpers\lock;

class RedisExtensionConnection implements RedisConnectionInterface
{

    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param array $options Example ['NX', 'EX' => 60]
     * @return mixed
     */
    public function set(string $key, $value, array $options)
    {
        return $this->redis->set($key, $value, $options);
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function eval($script, $args = [], $numKeys = 0)
    {
        return $this->redis->eval($script, $args, $numKeys);
    }

    public function del(array $keys)
    {
        return $this->redis->del($keys);
    }
}

