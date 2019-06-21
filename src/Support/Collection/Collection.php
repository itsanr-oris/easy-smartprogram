<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/21
 * Time: 10:58 AM
 */

namespace EasySmartProgram\Support\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class Collection
 * @package EasySmartProgram\Support\Collection
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, Jsonable, JsonSerializable, Arrayable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $callable = function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            }

            if ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            }

            if (is_object($value) && method_exists($value, 'toArray')) {
                return $value->toArray();
            }

            return $value;
        };

        return array_map($callable, $this->items);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * @param      $key
     * @param null $default
     * @return array|mixed|null
     */
    public function get($key, $default = null)
    {
        $item = $this->items;

        foreach (explode('.', $key) as $segment) {
            if (!isset($item[$segment])) {
                return $default;
            }
            $item = $item[$segment];
        }

        return $item;
    }
}