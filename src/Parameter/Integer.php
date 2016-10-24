<?php
namespace Glagol\Overriding\Parameter;

class Integer implements Type
{
    public function isSameTypeAs($value): bool
    {
        return is_int($value);
    }
}