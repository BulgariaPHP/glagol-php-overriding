<?php
declare(strict_types = 1);
namespace GlagolTest\Overrding\Parameter;

use Glagol\Overriding\Parameter\Optional;
use Glagol\Overriding\Parameter\Type;
use PHPUnit_Framework_TestCase;

class OptionalTest extends PHPUnit_Framework_TestCase
{
    public function testIsSameTypeAsShouldProxyToTheEnclosedTypeIsSameTypeAs()
    {
        $type = $this->getMockBuilder(Type::class)->getMock();
        $type->method('isSameTypeAs')->willReturn(false);

        $this->assertFalse((new Optional($type))->isSameTypeAs('dsads'));

        $type = $this->getMockBuilder(Type::class)->getMock();
        $type->method('isSameTypeAs')->willReturn(true);

        $this->assertTrue((new Optional($type))->isSameTypeAs(123));
    }
}
