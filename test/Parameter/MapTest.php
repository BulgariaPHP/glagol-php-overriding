<?php
declare(strict_types = 1);
namespace GlagolTest\Overrding\Parameter;

use Glagol\Overriding\Parameter\Integer;
use Glagol\Overriding\Parameter\Map;
use Glagol\Overriding\Parameter\Str;
use Glagol\Overriding\Parameter\Type;
use PHPUnit_Framework_TestCase;

class MapTest extends PHPUnit_Framework_TestCase
{
    public function testIsSameTypeShouldReturnFalseWhenArgumentIsNotAnArrayOrTraversable()
    {
        $type1 = $this->getMockBuilder(Type::class)->getMock();
        $type2 = $this->getMockBuilder(Type::class)->getMock();

        $mapParam = new Map($type1, $type2);

        $this->assertFalse($mapParam->isSameTypeAs(new \stdClass()));
    }

    public function testIsSameTypeShouldReturnFalseWhenContainingDataWithWrongData()
    {
        $type1 = $this->getMockBuilder(Type::class)->getMock();
        $type2 = $this->getMockBuilder(Type::class)->getMock();

        $type1->method('isSameTypeAs')->willReturn(false);

        $mapParam = new Map($type1, $type2);

        $this->assertFalse($mapParam->isSameTypeAs([
            'dummy' => 'val'
        ]));
    }

    public function testIsSameTypeShouldReturnFalseWhenContainingDataWithWrongData2()
    {
        $type1 = $this->getMockBuilder(Type::class)->getMock();
        $type2 = $this->getMockBuilder(Type::class)->getMock();

        $type2->method('isSameTypeAs')->willReturn(false);

        $mapParam = new Map($type1, $type2);

        $this->assertFalse($mapParam->isSameTypeAs([
            'dummy' => 'val'
        ]));
    }

    public function testIsSameTypeShouldReturnTrueWhenTypesValidate()
    {
        $type1 = $this->getMockBuilder(Type::class)->getMock();
        $type2 = $this->getMockBuilder(Type::class)->getMock();

        $type2->method('isSameTypeAs')->willReturn(true);
        $type1->method('isSameTypeAs')->willReturn(true);

        $mapParam = new Map($type1, $type2);

        $this->assertTrue($mapParam->isSameTypeAs([
            'dummy' => 'val'
        ]));
    }

    public function testIsSameTypeWillReturnFalseWithStringIntegerMapWithWrongData()
    {
        $mapParam = new Map(new Str(), new Integer());

        $this->assertFalse($mapParam->isSameTypeAs([
            'dummy' => 32,
            'dummy2' => 324.2,
        ]));
    }

    public function testIsSameTypeWillReturnTrueWithStringIntegerMapWithCorrectData()
    {
        $mapParam = new Map(new Str(), new Integer());

        $this->assertTrue($mapParam->isSameTypeAs([
            'dummy' => 32,
            'dummy2' => 324,
        ]));
    }
}
