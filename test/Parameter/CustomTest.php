<?php
declare(strict_types = 1);
namespace GlagolTest\Overrding\Parameter;

use Glagol\Overriding\Overrider;
use Glagol\Overriding\Parameter\ClassNotFoundException;
use Glagol\Overriding\Parameter\Custom;
use PHPUnit_Framework_TestCase;

class CustomTest extends PHPUnit_Framework_TestCase
{
    public function testShouldThrowExceptionWhenCustomTypeClassWasNotFound()
    {
        $this->expectException(ClassNotFoundException::class);

        new Custom('\NonExistingNamespace\NonExistingClassName');
    }

    public function testShouldSetTypeWhenClassExists()
    {
        $this->assertTrue((new Custom(\stdClass::class))->isSameTypeAs(new \stdClass()));
    }
}
