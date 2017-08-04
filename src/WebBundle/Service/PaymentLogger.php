<?php

namespace WebBundle\Service;


use Monolog\Logger;

class PaymentLogger
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
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