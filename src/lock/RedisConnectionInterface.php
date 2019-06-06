<?php

namespace taobig\helpers\lock;

interface RedisConnectionInterface
{

    /**
     * @param string $key
     * @param mixed $value
     * @param array $options Example ['NX', 'EX' => 60]
     * @return mixed
     */
    public function set(string $key, $value, array $options);

    public function get(string $key);

    public function eval($script, $args = [], $numKeys = 0);

    public function del(array $keys);

}

