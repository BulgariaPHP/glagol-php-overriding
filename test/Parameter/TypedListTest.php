<?php
declare(strict_types = 1);
namespace GlagolTest\Overrding\Parameter;

use Glagol\Overriding\Parameter\Str;
use Glagol\Overriding\Parameter\Type;
use Glagol\Overriding\Parameter\TypedList;
use PHPUnit_Framework_TestCase;

class TypedListTest extends PHPUnit_Framework_TestCase
{
    public function testIsSameTypeAsShouldReturnFalseWhenValueIsNotArrayOrTraversable()
    {
        $typedList = new TypedList($this->getMockBuilder(Type::class)->getMock());

        $this->assertFalse($typedList->isSameTypeAs(new \stdClass()));
    }

    public function testIsSameTypeAsShouldReturnFalseWheInnerTypeIsSameTypeAsReturnsFalse()
    {
        $type = $this->getMockBuilder(Type::class)->getMock();
        $type->method('isSameTypeAs')->willReturn(false);
        $typedList = new TypedList($type);

        $this->assertFalse($typedList->isSameTypeAs(['blah', 'blah2']));
    }

    public function testIsSameTypeAsShouldReturnTrueWithStringList()
    {
        $typedList = new TypedList(new Str());

        $this->assertTrue($typedList->isSameTypeAs(['blah', 'blah2']));
    }

    public function testIsSameTypeAsShouldReturnFalseWithStringListContainingWrongValues()
    {
        $typedList = new TypedList(new Str());

        $this->assertFalse($typedList->isSameTypeAs(['blah', 23]));
    }

    public function testIsSameTypeAsShouldReturnTrueWhenTheInnerTypeIsSameTypeAsReturnsTrue()
    {
        $type = $this->getMockBuilder(Type::class)->getMock();
        $type->method('isSameTypeAs')->willReturn(true);

        $typedList = new TypedList($type);

        $this->assertTrue($typedList->isSameTypeAs([]));
    }
}
