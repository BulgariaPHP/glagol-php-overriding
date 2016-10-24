<?php
namespace Glagol\Overriding\Parameter;

use InvalidArgumentException;

/**
 * Exception thrown if the provided 'type' is a non-existing class
 */
class ClassNotFoundException extends InvalidArgumentException
{
}