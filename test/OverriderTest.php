<?php
declare(strict_types=1);
namespace GlagolTest\Overriding;

use Glagol\Overriding\DuplicateRuleException;
use Glagol\Overriding\Overrider;
use Glagol\Overriding\Type\Integer;
use Glagol\Overriding\Type\Real;
use Glagol\Overriding\Type\Str;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_TestCase;

class OverriderTest extends PHPUnit_Framework_TestCase
{
    public function testShouldMatchRuleWithOneIntegerParamUsingOneRule()
    {
        $overrider = new Overrider();
        $overrider->override(function (int $a) {
            $this->assertSame($a, 5);
        }, new Integer());

        $overrider->execute(5);

        $this->assertEquals($this->getCount(), 1);
    }

    public function testShouldMatchRuleWithOneIntegerParamUsingTwoOtherRules()
    {
        $overrider = new Overrider();

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
        $overrider = new Overrider();

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
        $overrider = new Overrider();

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

    public function testShouldThrowExceptionWhenTryingToPutOverrideWithDuplicateSignature()
    {
        $this->expectException(DuplicateRuleException::class);

        $overrider = new Overrider();

        $overrider->override(function (string $a, float $c) {
        }, new Str(), new Real());

        $overrider->override(function (string $c, float $d) {
        }, new Str(), new Real());
    }
}
