<?php

namespace AirTable\Requests;


/**
 * Class RateLimit
 * @package AirTable
 */
class RateLimit
{
    /**
     * @var
     */
    protected $rateLimit;

    /**
     * @var int
     */
    protected $lastRequestTime;

    /**
     * @var int
     */
    protected $requestCount = 0;

    /**
     * @var
     */
    private $sleepTime;

    /**
     * RateLimit constructor.
     * @param int $rateLimit
     * @param int $sleepTime
     */
    public function __construct(int $rateLimit, int $sleepTime = 1000)
    {
        $this->setRateLimit($rateLimit);
        $this->setSleepTime($sleepTime);
    }

    /**
     * @return mixed
     */
    protected function getRateLimit() : int
    {
        return $this->rateLimit;
    }

    /**
     * @param mixed $rateLimit
     */
    protected function setRateLimit(int $rateLimit): void
    {
        $this->rateLimit = $rateLimit;
    }

    /**
     * @return mixed
     */
    protected function getLastRequestTime() : int
    {
        return $this->lastRequestTime;
    }

    /**
     * @param mixed $lastRequestTime
     */
    protected function setLastRequestTime(int $lastRequestTime): void
    {
        $this->lastRequestTime = $lastRequestTime;
    }

    /**
     * @return mixed
     */
    protected function getRequestCount() : int
    {
        return $this->requestCount;
    }

    /**
     * @param mixed $requestCount
     */
    protected function setRequestCount(int $requestCount): void
    {
        $this->requestCount = $requestCount;
    }

    /**
     * @return int
     */
    private function getSleepTime(): int
    {
        return $this->sleepTime;
    }

    /**
     * @param int $sleepTime
     */
    private function setSleepTime(int $sleepTime): void
    {
        $this->sleepTime = $sleepTime;
    }


    /**
     *
     */
    public function start() : void {
        if(!is_null($this->getRateLimit())) {
            $this->setLastRequestTime(time());
            $this->setRequestCount($this->getRequestCount()+1);

            while ($this->getLastRequestTime() == time() && $this->getRequestCount() > $this->getRateLimit()) {
                usleep($this->getSleepTime());
            }

            if ($this->getLastRequestTime() != time()) {
                $this->setRequestCount(0);
            }
        }
    }
}