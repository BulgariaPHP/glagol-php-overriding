<?php
declare(strict_types=1);
namespace Glagol\Overriding;

class Overrider
{
    /**
     * @var Rule[]
     */
    private $overrides = [];

    public function override(callable $body, Parameter\Type ...$signature)
    {
        $key = $this->createKeyFromSignature(...$signature);

        if (array_key_exists($key, $this->overrides)) {
            throw new DuplicateRuleException("Cannot override rule, same signature already defined");
        }

        $this->overrides[$key] = new Rule($body, ...$signature);
    }

    /**
     * @param array ...$args
     *
     * @throws CannotMatchConstructorException
     */
    public function execute(...$args)
    {
        foreach ($this->overrides as $override)
        {
            if ($override->matches(...$args))
            {
                $override->run(...$args);

                return;
            }
        }

        throw new CannotMatchConstructorException();
    }

    /**
     * @param Parameter\Type[] ...$signature
     *
     * @return string
     */
    private function createKeyFromSignature(Parameter\Type ...$signature): string
    {
        $key = "";
        foreach ($signature as $type) {
            $key .= get_class($type);
        }

        return $key;
    }
}