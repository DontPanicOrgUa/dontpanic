<?php

namespace AdminBundle\Service;


use Monolog\Logger;

class DebugLogger
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function debug(string $message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }

    public function error(string $message, array $context = [])
    {
        $this->logger->error($message, $context);
    }

    public function info(string $message, array $context = [])
    {
        $this->logger->info($message, $context);
    }
}