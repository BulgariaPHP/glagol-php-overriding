<?php
namespace Glagol\Overriding\Type;

class Integer implements Type
{
    public function isSameTypeAs($value): bool
    {
        return is_int($value);
    }
}