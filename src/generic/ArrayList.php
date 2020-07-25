<?php

namespace taobig\helpers\generic;


use taobig\helpers\generic\exception\IndexOutOfBoundsException;
use taobig\helpers\generic\exception\OffsetTypeErrorException;
use taobig\helpers\generic\exception\UnsupportedOperationException;

class ArrayList implements \IteratorAggregate, \ArrayAccess
{

    protected array $elements = [];

    public function __construct(array $elements = [])
    {
        $this->elements = array_values($elements);
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function first()
    {
        return reset($this->elements);
    }

    public function last()
    {
        return end($this->elements);
    }

    public function remove(int $fromIndex, int $length = 1): ArrayList
    {
        $list = $this->elements;//clone array(copy on write)
        $removeList = array_splice($list, $fromIndex, $length);
        $this->elements = $list;
        return new ArrayList($removeList);
    }

    public function removeElement($element, bool $strict = true): bool
    {
        $key = array_search($element, $this->elements, $strict);
        if ($key === false) {
            return false;
        }
        $list = $this->elements;//clone array(copy on write)
        unset($list[$key]);
        $this->elements = array_values($list);
        return true;
    }

    public function offsetExists($offset)
    {
//        return $this->containsKey($offset);
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        throw new UnsupportedOperationException();
    }

    public function set($offset, $value)
    {
        if (!is_int($offset)) {
            throw new OffsetTypeErrorException();
        }
        if (!isset($this->elements[$offset])) {
            throw new IndexOutOfBoundsException();
        }
        $this->elements[$offset] = $value;
        return $this;
    }

    public function contains($element, bool $strict = false)
    {
        return in_array($element, $this->elements, $strict);
    }

    public function indexOf($element, bool $strict = true)
    {
        return array_search($element, $this->elements, $strict);
    }

    public function get($offset)
    {
        if (!is_int($offset)) {
            throw new OffsetTypeErrorException();
        }
        if (!isset($this->elements[$offset])) {
            throw new IndexOutOfBoundsException();
        }
        return $this->elements[$offset];
    }

    public function count()
    {
        return count($this->elements);
    }

    public function add($value)
    {
        $this->elements[] = $value;
        return $this;
    }

    public function addList(ArrayList $list)
    {
//        $this->elements = array_merge($this->elements, $list->toArray());
        array_push($this->elements, ...$list->elements);
        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->elements);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function slice(int $fromIndex, int $length): ArrayList
    {
//        return array_slice($this->elements, $fromIndex, $length, false);
        return new ArrayList(array_slice($this->elements, $fromIndex, $length));
    }

    public function push($value)
    {
        return $this->add($value);
    }

    public function pop()
    {
        return array_pop($this->elements);
    }

    public function shift()
    {
//        $this->remove(0);
        return array_shift($this->elements);
    }

    public function prepend($value)
    {
        return array_unshift($this->elements, $value);
    }

    public function join(string $glue = ''): string
    {
        return implode($glue, $this->elements);
    }

    public function unique($sort_flags = SORT_STRING): ArrayList
    {
        return new ArrayList(array_unique($this->elements, $sort_flags));
    }

}