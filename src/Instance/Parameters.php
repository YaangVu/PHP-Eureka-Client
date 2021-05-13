<?php

namespace YaangVu\EurekaClient\Instance;

/**
 * Class Parameters
 *
 * @package EurekaClient\Instance
 */
abstract class Parameters
{
    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    protected function set(string $key, $value): Parameters
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if (isset($this->parameters[$key]))
            return $this->parameters[$key];
        else return null;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return $this->parameters;
    }
}
