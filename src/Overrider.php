<?php
declare(strict_types=1);
namespace Glagol\Overriding;

class Overrider
{
    /**
     * @var Rule[]
     */
    private $overrides = [];

    /**
     * @var bool
     */
    private $isConstructor;

    /**
     * Parenting artifact real name
     *
     * @var string
     */
    private $artifactName;

    /**
     * Name of the method being overridden
     *
     * @var string
     */
    private $methodName;

    /**
     * Overrider constructor.
     *
     * @param bool $isConstructor
     * @param string $artifactName
     * @param string $methodName    Glagol DSL overridden method name
     */
    public function __construct(bool $isConstructor, string $artifactName, string $methodName = null)
    {
        $this->isConstructor = $isConstructor;
        $this->artifactName = $artifactName;
        $this->methodName = $methodName;
    }


    public function override(callable $body, Parameter\Type ...$signature): Rule
    {
        return $this->overrides[] = new Rule($body, ...$signature);
    }

    /**
     * @param array ...$args
     * @return mixed
     */
    public function execute(...$args)
    {
        $overrides = $this->overrides;

        usort($overrides, function (Rule $a, Rule $b): int {
            return $a->hasWhen() ? -1 : 1;
        });

        foreach ($overrides as $override)
        {
            if ($override->matches(...$args))
            {
                return $override->run(...$args);
            }
        }

        if ($this->isConstructor) {
            throw new NoMatchingConstructorException($this->artifactName);
        }

        throw new NoMatchingMethodException($this->artifactName, $this->methodName);
    }
}
