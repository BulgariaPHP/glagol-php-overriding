<?php
namespace Glagol\Overriding;

use InvalidArgumentException;

class NoMatchingMethodException extends InvalidArgumentException
{
    public function __construct(string $artifactName, string $methodName)
    {
        parent::__construct("Cannot match method {$artifactName}::{$methodName}");
    }
}