<?php
declare(strict_types=1);
namespace Glagol\Overriding;

use Glagol\Overriding\Parameter\Optional;
use Glagol\Overriding\Parameter\Type;

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

    /**
     * @var callable
     */
    private $whenCondition;

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

        if (0 === count($args) && 0 === count($this->signature)) {
            return true;
        }

        $position = false;
        foreach ($args as $position => $arg) {
            if (!$this->signature[$position]->isSameTypeAs($arg)) {
                return false;
            }
        }

        for ($i = $position === false ? 0 : $position + 1; $i < count($this->signature); $i++) {
            if (!$this->signature[$i] instanceof Optional) {
                return false;
            }
        }

        if ($this->hasWhen() && !$this->checkWhenCondition(...$args)) {
            return false;
        }

        return true;
    }

    private function checkWhenCondition(...$args): bool
    {
        $when = $this->whenCondition;

        return $when(...$args);
    }

    public function hasWhen(): bool
    {
        return $this->whenCondition !== null;
    }

    public function when(callable $whenCondition): self
    {
        $this->whenCondition = $whenCondition;

        return $this;
    }

    public function run(...$args)
    {
        $body = $this->body;

        return $body(...$args);
    }
}
