<?php
namespace Glagol\Overriding\Type;

use Traversable;

class Map implements Collection
{
    /**
     * @var Type
     */
    private $key;

    /**
     * @var Type
     */
    private $value;

    public function __construct(Type $key, Type $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return Type
     */
    public function getKey(): Type
    {
        return $this->key;
    }

    /**
     * @return Type
     */
    public function getValue(): Type
    {
        return $this->value;
    }

    /**
     * TODO create a different case using the Map Glagol collection (to be created soon)
     *
     * @param $value
     * @return bool
     */
    public function isSameTypeAs($value): bool
    {
        if (!is_array($value) && !$value instanceof Traversable) {
            return false;
        }

        foreach ($value as $key => $val) {
            if (!($this->key->isSameTypeAs($key) && $this->value->isSameTypeAs($val))) {
                return false;
            }
        }

        return true;
    }
}