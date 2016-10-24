<?php
declare(strict_types=1);
namespace Glagol\Overriding\Parameter;


class Str implements Type
{
    /**
     * String constructor.
     */
    public function __construct()
    {
    }

    public function isSameTypeAs($value): bool
    {
        return is_string($value);
    }
}