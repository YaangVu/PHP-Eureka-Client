<?php

namespace YaangVu\EurekaClient\Instance;

/**
 * Class Instance
 *
 * @package EurekaClient\Instance
 */
class Instance extends Parameters
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->setInstanceId("Unknown")
             ->setHostName('Unknown')
             ->setApp("Unknown")
             ->setIpAddr('127.0.0.1')
             ->setPort(8000)
             ->setSecurePort(433)
             ->setHomePageUrl('http://localhost')
             ->setStatusPageUrl('http://localhost/status')
             ->setHealthCheckUrl('http://localhost/health-check')
             ->setSecureHealthCheckUrl('https://localhost/health-check')
             ->setVipAddress('unknown_vip_address')
             ->setSecureVipAddress('unknown_secure_vip_address')
            //->setMetadata(new Metadata())
             ->setDataCenterInfo(new DataCenterInfo());
    }

    /**
     * @param string $instanceId
     *
     * @return $this
     */
    public function setInstanceId(string $instanceId): Instance
    {
        return $this->set('instanceId', $instanceId);
    }

    /**
     * @param string $hostName
     *
     * @return $this
     */
    public function setHostName(string $hostName): Instance
    {
        return $this->set('hostName', $hostName);
    }

    /**
     * @param string $app
     *
     * @return $this
     */
    public function setApp(string $app): Instance
    {
        return $this->set('app', $app);
    }

    /**
     * @param string $ipAddr
     *
     * @return $this
     */
    public function setIpAddr(string $ipAddr): Instance
    {
        return $this->set('ipAddr', $ipAddr);
    }

    /**
     * @param int  $port
     * @param bool $enabled
     *
     * @return $this
     */
    public function setPort(int $port, bool $enabled = true): Instance
    {
        return $this->set('port', [
            '$'        => $port,
            '@enabled' => ($enabled) ? 'true' : 'false'
        ]);
    }

    /**
     * @param string $port
     * @param bool   $enabled
     *
     * @return $this
     */
    public function setSecurePort(int $port, bool $enabled = true): Instance
    {
        return $this->set('securePort', [
            '$'        => $port,
            '@enabled' => ($enabled) ? 'true' : 'false'
        ]);
    }

    /**
     * @param string $homePageUrl
     *
     * @return $this
     */
    public function setHomePageUrl(string $homePageUrl): Instance
    {
        return $this->set('homePageUrl', $homePageUrl);
    }

    /**
     * @param string $statusPageUrl
     *
     * @return $this
     */
    public function setStatusPageUrl(string $statusPageUrl): Instance
    {
        return $this->set('statusPageUrl', $statusPageUrl);
    }

    /**
     * @param string $healthCheckUrl
     *
     * @return $this
     */
    public function setHealthCheckUrl(string $healthCheckUrl): Instance
    {
        return $this->set('healthCheckUrl', $healthCheckUrl);
    }

    /**
     * @param string $secureHealthCheckUrl
     *
     * @return $this
     */
    public function setSecureHealthCheckUrl(string $secureHealthCheckUrl): Instance
    {
        return $this->set('secureHealthCheckUrl', $secureHealthCheckUrl);
    }

    /**
     * @param string $vipAddress
     *
     * @return $this
     */
    public function setVipAddress(string $vipAddress): Instance
    {
        return $this->set('vipAddress', $vipAddress);
    }

    /**
     * @param string $secureVipAddress
     *
     * @return $this
     */
    public function setSecureVipAddress(string $secureVipAddress): Instance
    {
        return $this->set('secureVipAddress', $secureVipAddress);
    }

    /**
     * @param Metadata $metadata
     *
     * @return $this
     */
    public function setMetadata(Metadata $metadata): Instance
    {
        return $this->set('metadata', $metadata->export());
    }

    /**
     * @param DataCenterInfo $dataCenterInfo
     *
     * @return $this
     */
    public function setDataCenterInfo(DataCenterInfo $dataCenterInfo): Instance
    {
        return $this->set('dataCenterInfo', $dataCenterInfo->export());
    }
}
