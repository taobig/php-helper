<?php

namespace taobig\helpers\lock;

use yii\redis\Connection;

class YiiRedisConnection implements RedisConnectionInterface
{

    protected $redis;

    public function __construct(Connection $redis)
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
        $params = [];
        foreach ($options as $_key => $option) {
            if (is_string($_key)) {
                $params[] = $_key;
                $params[] = $option;
            } else {
                $params[] = $option;
            }
        }
        return $this->redis->set($key, $value, $options);
        //$this->redis->executeCommand('SET', [$key, $value, 'EX', 60, 'NX']);
//        array_unshift($options, $key);
//        array_unshift($options, $value);
//        return $this->redis->executeCommand('SET', $options);
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function eval($script, $args = [], $numKeys = 0)
    {
        return $this->redis->eval($script, $numKeys, ...$args);
    }

    public function del(array $keys)
    {
        return $this->redis->del($keys);
    }
}

