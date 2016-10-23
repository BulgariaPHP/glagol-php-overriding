<?php
declare(strict_types=1);
namespace Glagol\Overriding\Type;

interface Type
{
    public function isSameTypeAs($value): bool;
}