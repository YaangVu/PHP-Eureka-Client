<?php

namespace EurekaClient\Instance;

/**
 * Class DataCenterInfo
 *
 * @package EurekaClient\Instance
 */
class DataCenterInfo extends Parameters
{
    /**
     * DataCenterInfo constructor.
     */
    public function __construct()
    {
        $this
            ->setName('MyOwn')
            ->setClass('com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo');
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): DataCenterInfo
    {
        return $this->set('name', $name);
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class): DataCenterInfo
    {
        return $this->set('@class', $class);
    }

    /**
     * @param Metadata $metadata
     *
     * @return $this
     */
    public function setMetadata(Metadata $metadata): DataCenterInfo
    {
        return $this->set('metadata', $metadata->export());
    }
}
