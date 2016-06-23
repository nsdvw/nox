<?php
namespace PricklyNut\NoxChallenge\DI;

class Container
{
    /**
     * Params and services
     * @var array
     */
    protected $values = array();

    /**
     * @var array
     */
    protected $keys = array();

    public function __set($id, $value)
    {
        $this->values[$id] = $value;
        $this->keys[] = $id;
    }

    public function __get($id)
    {
        if (!isset($this->values[$id])) {
            throw new \InvalidArgumentException(
                sprintf('Value %s is not defined', $id)
            );
        }

        return is_callable($this->values[$id])
            ? $this->values[$id]($this)
            : $this->values[$id];
    }

    /**
     * "Singleton" method to have only one instance of object
     * @param $callable
     * @return callable
     */
    public function asShared($callable)
    {
        return function ($c) use ($callable)
        {
            static $object;
            if (is_null($object)) {
                $object = $callable($c);
            }
            return $object;
        };
    }
}
