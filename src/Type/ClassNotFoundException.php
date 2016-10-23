<?php
namespace Glagol\Overriding\Type;

use InvalidArgumentException;

/**
 * Exception thrown if the provided 'type' is a non-existing class
 */
class ClassNotFoundException extends InvalidArgumentException
{
}