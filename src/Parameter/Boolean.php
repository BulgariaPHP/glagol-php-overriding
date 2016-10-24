<?php
declare(strict_types=1);
namespace Glagol\Overriding\Parameter;

class Boolean implements Type
{
    public function isSameTypeAs($value): bool
    {
        return is_bool($value);
    }
}