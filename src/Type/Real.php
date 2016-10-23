<?php
declare(strict_types=1);
namespace Glagol\Overriding\Type;


class Real implements Type
{
    public function isSameTypeAs($value): bool
    {
        return is_float($value);
    }
}