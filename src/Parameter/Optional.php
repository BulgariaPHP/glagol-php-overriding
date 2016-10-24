<?php
declare(strict_types = 1);
namespace Glagol\Overriding\Parameter;

class Optional implements Type
{
    /**
     * @var Type
     */
    private $parameter;

    public function __construct(Type $parameter)
    {
        $this->parameter = $parameter;
    }

    public function isSameTypeAs($value): bool
    {
        return $this->parameter->isSameTypeAs($value);
    }
}