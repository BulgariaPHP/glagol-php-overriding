<?php
declare(strict_types=1);
namespace Glagol\Overriding\Parameter;

interface Type
{
    public function isSameTypeAs($value): bool;
}