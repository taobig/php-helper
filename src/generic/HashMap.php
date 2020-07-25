<?php

namespace taobig\helpers\generic;

use taobig\helpers\generic\exception\KeyTypeErrorException;

/**
 * Class HashMap
 * @package taobig\helpers\generic
 * 注意，因为PHP内置数组的特性，key 会有如下的强制转换：
 *     包含有合法整型值的字符串会被转换为整型。例如键名 "8" 实际会被储存为 8。但是 "08" 则不会强制转换，因为其不是一个合法的十进制数值。
 *     浮点数也会被转换为整型，意味着其小数部分会被舍去。例如键名 8.7 实际会被储存为 8。
 *     布尔值也会被转换成整型。即键名 true 实际会被储存为 1 而键名 false 会被储存为 0。
 *     Null 会被转换为空字符串，即键名 null 实际会被储存为 ""。
 *     数组和对象不能被用为键名。坚持这么做会导致警告：Illegal offset type。
 * 所以 ->put("1", 1) or ->put(1, 1) 都可以通过->get(1) or ->get("1")取出数据
 */
class HashMap
{

    protected array $hashTable = [];

    protected function checkKey($key): void
    {
        if (is_int($key) || is_string($key)) {
            return;
        }
        throw new KeyTypeErrorException("The key should be either a string or an integer");
    }

    /**
     * @param string|int $key
     * @param $value
     * @return mixed|null
     * @throws KeyTypeErrorException
     */
    public function put($key, $value)
    {
        $this->checkKey($key);
        if (!array_key_exists($key, $this->hashTable)) {
            $this->hashTable[$key] = $value;
            return null;
        }
        $oldValue = $this->hashTable[$key];
        $this->hashTable[$key] = $value;
        return $oldValue;
    }

    public function putAll(HashMap $map): void
    {
        if ($map->size() > 0) {
            $keys = $map->keys();
            foreach ($keys as $key) {
                $this->put($key, $map->get($key));
            }
        }
    }

    /**
     * @param string|int $key
     * @return mixed|null
     * @throws KeyTypeErrorException
     */
    public function get($key)
    {
        $this->checkKey($key);
        if (array_key_exists($key, $this->hashTable)) {
            return $this->hashTable[$key];
        }
        return null;
    }

    public function keys(): array
    {
        return array_keys($this->hashTable);
    }

    public function values(): array
    {
        return array_values($this->hashTable);
    }

    /**
     * @param string|int $key
     * @return mixed|null
     * @throws KeyTypeErrorException
     */
    public function remove($key)
    {
        $this->checkKey($key);
        $newHashTable = [];
        if (array_key_exists($key, $this->hashTable)) {
            $tempValue = $this->hashTable[$key];
            while ($curValue = current($this->hashTable)) {
                if (!(key($this->hashTable) == $key)) {
                    $newHashTable[key($this->hashTable)] = $curValue;
                }
                next($this->hashTable);
            }
            $this->hashTable = $newHashTable;
            return $tempValue;
        }
        return null;
    }

    public function removeAll(): void
    {
        $this->hashTable = [];
    }

    public function containsValue($value): bool
    {
        while ($curValue = current($this->hashTable)) {
            if ($curValue == $value) {
                return true;
            }
            next($this->hashTable);
        }
        return false;
    }

    /**
     * @param string|int $key
     * @return bool
     * @throws KeyTypeErrorException
     */
    public function containsKey($key): bool
    {
        $this->checkKey($key);
        if (array_key_exists($key, $this->hashTable)) {
            return true;
        } else {
            return false;
        }
    }

    public function size(): int
    {
        return count($this->hashTable);
    }

    public function isEmpty(): bool
    {
        return (count($this->hashTable) == 0);
    }

    public function isNotEmpty(): bool
    {
        return (count($this->hashTable) != 0);
    }

    public function toJson(): string
    {
        if (empty($this->hashTable)) {
            return json_encode(null);
        }
        //先转换成对象再json_encode；避免[0=>0, 1=>1]这样的数据在json_encode时会转换成数组
        return json_encode((object)$this->hashTable, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

}
