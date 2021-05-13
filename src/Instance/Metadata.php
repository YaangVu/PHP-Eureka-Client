<?php

namespace YaangVu\EurekaClient\Instance;

/**
 * Class Metadata
 *
 * @package EurekaClient\Instance
 */
class Metadata extends Parameters
{

    public function set(string $key, $value): Parameters
    {
        return parent::set($key, $value);
    }
}
