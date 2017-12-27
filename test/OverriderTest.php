<?php
declare(strict_types=1);
namespace GlagolTest\Overriding;

use Glagol\Overriding\NoMatchingConstructorException;
use Glagol\Overriding\DuplicateRuleException;
use Glagol\Overriding\NoMatchingMethodException;
use Glagol\Overriding\Overrider;
use Glagol\Overriding\Parameter\Integer;
use Glagol\Overriding\Parameter\Optional;
use Glagol\Overriding\Parameter\Real;
use Glagol\Overriding\Parameter\Str;
use Glagol\Overriding\Rule;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_TestCase;

class OverriderTest extends PHPUnit_Framework_TestCase
{
    public function testShouldMatchRuleWithOneIntegerParamUsingOneRule()
    {
        $overrider = new Overrider(false, 'OverriderTest', 'test');
        $overrider->override(function (int $a) {
            $this->assertSame($a, 5);
        }, new Integer());

        $overrider->execute(5);

        $this->assertEquals($this->getCount(), 1);
    }

    public function testShouldMatchRuleWithOneIntegerParamUsingTwoOtherRules()
    {
        $overrider = new Overrider(false, 'OverriderTest', 'test');

        $overrider->override(function (int $a) {
            $this->assertSame($a, 5);
        }, new Integer());

        $overrider->override(function (string $a) {
            throw new PHPUnit_Framework_Exception("This should not be called");
        }, new Str());

        $overrider->override(function (float $a, int $b) {
            throw new PHPUnit_Framework_Exception("This should not be called");
        }, new Real(), new Integer());

        $overrider->execute(5);

        $this->assertEquals($this->getCount(), 1);
    }

    public function testShouldMatchRuleWithOneStringParamUsingThreeRules()
    {
        $overrider = new Overrider(false, 'OverriderTest', 'test');

        $overrider->override(function (int $a) {
            throw new PHPUnit_Framework_Exception("This should not be called");
        }, new Integer());

        $overrider->override(function (string $a) {
            $this->assertSame($a, "testing");
        }, new Str());

        $overrider->override(function (float $a, int $b) {
            throw new PHPUnit_Framework_Exception("This should not be called");
        }, new Real(), new Integer());

        $overrider->execute("testing");

        $this->assertEquals($this->getCount(), 1);
    }

    public function testShouldMatchRuleWithOneStringAndAFloatParamsUsingThreeRules()
    {
        $overrider = new Overrider(false, 'OverriderTest', 'test');

        $overrider->override(function (int $a) {
            throw new PHPUnit_Framework_Exception("This should not be called");
        }, new Integer());

        $overrider->override(function (string $a, float $c) {
            $this->assertSame($a, "testing2");
            $this->assertSame($c, (float) 3);
        }, new Str(), new Real());

        $overrider->override(function (float $a, int $b) {
            throw new PHPUnit_Framework_Exception("This should not be called");
        }, new Real(), new Integer());

        $overrider->execute("testing2", (float) 3);

        $this->assertEquals($this->getCount(), 2);
    }

    public function testShouldThrowConstructorNotFoundExceptionWhenThereAreMoreRequiredParameters()
    {
        $this->expectException(NoMatchingConstructorException::class);
        $this->expectExceptionMessage('Cannot match constructor for OverriderTest');

        $overrider = new Overrider(true, 'OverriderTest');

        $overrider->override(function (string $a, float $c, float $d) {
        }, new Str(), new Real(), new Real());

        $overrider->execute("bla", 2.3);
    }

    public function testShouldThrowMethodNotFoundExceptionWhenThereAreMoreRequiredParameters()
    {
        $this->expectException(NoMatchingMethodException::class);
        $this->expectExceptionMessage('Cannot match method OverriderTest::test');

        $overrider = new Overrider(false, 'OverriderTest', 'test');

        $overrider->override(function (string $a, float $c, float $d) {
        }, new Str(), new Real(), new Real());

        $overrider->execute("bla", 2.3);
    }

    public function testShouldMatchConstructorWithEmptyOptionalParameters()
    {
        $overrider = new Overrider(true, 'OverriderTest');

        $overrider->override(function (string $a, float $c, float $d = null) {
            $this->assertSame("bla", $a);
            $this->assertSame(2.3, $c);
            $this->assertNull($d);
        }, new Str(), new Real(), new Optional(new Real()));

        $overrider->execute("bla", 2.3);

        $this->assertEquals($this->getCount(), 3);
    }

    public function testFluidInterface()
    {
        $overrider = new Overrider(true, 'OverriderTest');

        $this->assertInstanceOf(Rule::class, $overrider->override(function (int $a) {}, new Integer()));
    }

    public function testShouldExecuteOnlyOnWhenCondition()
    {
        $overrider = new Overrider(true, 'OverriderTest');

        $rule = $overrider->override(function (string $a, float $c, float $d = null) {
            $this->assertSame("abv", $a);
            $this->assertSame(2.3, $c);
            $this->assertNull($d);
        }, new Str(), new Real(), new Optional(new Real()));

        $rule->when(function (string $a, float $c, float $d = null):bool {
            return $a === "abv";
        });

        $overrider->override(function (string $a, float $c, float $d = null) {
            $this->assertSame("bla", $a);
            $this->assertSame(5.4, $c);
            $this->assertSame(7.6, $d);
        }, new Str(), new Real(), new Optional(new Real()));

        $overrider->execute("abv", 2.3);
        $overrider->execute("bla", 5.4, 7.6);

        $this->assertEquals($this->getCount(), 6);
    }

    public function testNotMatchingWhenArgsAreMoreThanExpected()
    {
        $this->expectException(NoMatchingConstructorException::class);

        $overrider = new Overrider(true, 'OverriderTest');

        $overrider->override(function (string $a, float $c) {
            $this->assertSame("abv", $a);
            $this->assertSame(2.3, $c);
        }, new Str(), new Real());

        $overrider->execute("abv", 2.3, 123);
    }

    public function testExecuteARuleWithEmptySignature()
    {
        $overrider = new Overrider(true, 'OverriderTest');

        $overrider->override(function () {
            return true;
        });

        $overrider->override(function (int $a) {
            return false;
        }, new Integer());

        $overrider->override(function (string $a) {
            return false;
        }, new Str());

        $this->assertTrue($overrider->execute());
        $this->assertFalse($overrider->execute(123));
        $this->assertFalse($overrider->execute('123'));
    }

    public function testExecuteARuleWithOptionalSignaturePatternUsingNoArgs()
    {
        $overrider = new Overrider(true, 'OverriderTest');

        $overrider->override(function (float $b = null, float $c = null) {
            return true;
        });

        $overrider->override(function (int $a) {
            return false;
        }, new Integer());

        $overrider->override(function (string $a) {
            return false;
        }, new Str());

        $this->assertTrue($overrider->execute());
        $this->assertFalse($overrider->execute(123));
        $this->assertFalse($overrider->execute('123'));
    }

    public function testReturnValueShouldBeFromExecutedRule()
    {
        $overrider = new Overrider(true, 'OverriderTest');

        $overrider->override(function (int $a) {
            return $a;
        }, new Integer());

        $this->assertEquals(3, $overrider->execute(3));
        $this->assertEquals(4, $overrider->execute(4));
        $this->assertEquals(423, $overrider->execute(423));
    }
}
