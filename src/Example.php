<?php
namespace Glagol\Overriding;

use Glagol\Overriding\Type\Integer;
use Glagol\Overriding\Type\Real;
use Glagol\Overriding\Type\Str;

class Example
{
    public function __construct(...$args)
    {
        $overrider = new Overrider();

        $overrider->override(function (int $a, float $b) {
            // Something here
        }, new Integer(), new Real());

        $overrider->override(function (string $a) {
            // Something else here
        }, new Str());

        $overrider->execute($args);
    }
}