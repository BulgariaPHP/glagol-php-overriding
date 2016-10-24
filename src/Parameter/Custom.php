<?php
declare(strict_types=1);
namespace Glagol\Overriding\Parameter;

class Custom implements Type
{
    private $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        if (!class_exists($type)) {
            throw new ClassNotFoundException("Class '$type' not found");
        }

        $this->type = $type;
    }

    public function isSameTypeAs($value): bool
    {
        return $value === $this->type;
    }
}