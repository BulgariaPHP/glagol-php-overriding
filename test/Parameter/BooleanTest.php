<?php
declare(strict_types = 1);
namespace GlagolTest\Overriding\Parameter;

use Glagol\Overriding\Parameter\Boolean;
use PHPUnit_Framework_TestCase;

class BooleanTest extends PHPUnit_Framework_TestCase
{
    public function testShouldIdentifyTrueAsBoolean()
    {
        $this->assertTrue((new Boolean())->isSameTypeAs(true));
    }

    public function testShouldIdentifyFalseAsBoolean()
    {
        $this->assertTrue((new Boolean())->isSameTypeAs(false));
    }

    public function testShouldNotIdentifyStringAsBoolean()
    {
        $this->assertFalse((new Boolean())->isSameTypeAs("string"));
    }

    public function testShouldNotIdentifyIntAsBoolean()
    {
        $this->assertFalse((new Boolean())->isSameTypeAs(234));
    }

    public function testShouldNotIdentifyFloatAsBoolean()
    {
        $this->assertFalse((new Boolean())->isSameTypeAs(234.234));
    }
}
