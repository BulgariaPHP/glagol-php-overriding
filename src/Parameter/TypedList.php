<?php
namespace Glagol\Overriding\Parameter;

use Traversable;

class TypedList implements Collection
{
    /**
     * @var Type
     */
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function isSameTypeAs($value): bool
    {
        if (!is_array($value) && !$value instanceof Traversable) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->type->isSameTypeAs($item)) {
                return false;
            }
        }

        return true;
    }
}