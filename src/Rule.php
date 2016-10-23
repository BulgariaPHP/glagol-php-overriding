<?php
declare(strict_types=1);
namespace Glagol\Overriding;

use Glagol\Overriding\Type\Type;

class Rule
{
    /**
     * @var callable
     */
    private $body;

    /**
     * @var Type[]
     */
    private $signature;

    public function __construct(callable $body, Type ...$signature)
    {
        $this->body = $body;
        $this->signature = $signature;
    }

    public function matches(...$args): bool
    {
        if (count($args) > count($this->signature)) {
            return false;
        }

        foreach ($args as $position => $arg) {
            if (!$this->signature[$position]->isSameTypeAs($arg)) {
                return false;
            }
        }

        // TODO check if the result of the signature is optional params
        // if so - return true, otherwise false

        return true;
    }

    public function run(...$args)
    {
        $body = $this->body;

        $body(...$args);
    }
}