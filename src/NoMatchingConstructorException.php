<?php
declare(strict_types = 1);
namespace Glagol\Overriding;

use InvalidArgumentException;

class NoMatchingConstructorException extends InvalidArgumentException
{
    public function __construct(string $artifactName)
    {
        parent::__construct("Cannot match constructor for {$artifactName}");
    }
}