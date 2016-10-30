<?php
declare(strict_types=1);
namespace Glagol\Overriding;

class Overrider
{
    /**
     * @var Rule[]
     */
    private $overrides = [];

    public function override(callable $body, Parameter\Type ...$signature): Rule
    {
        return $this->overrides[] = new Rule($body, ...$signature);
    }

    /**
     * @param array ...$args
     *
     * @return self
     * @throws CannotMatchConstructorException
     */
    public function execute(...$args): self
    {
        $overrides = $this->overrides;

        usort($overrides, function (Rule $a, Rule $b): int {
            return $a->hasWhen() ? -1 : 1;
        });

        foreach ($overrides as $override)
        {
            if ($override->matches(...$args))
            {
                $override->run(...$args);

                return $this;
            }
        }

        throw new CannotMatchConstructorException();
    }
}