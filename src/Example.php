<?php
namespace Glagol\Overriding;

use Glagol\Overriding\Parameter\Integer;
use Glagol\Overriding\Parameter\Real;
use Glagol\Overriding\Parameter\Str;

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