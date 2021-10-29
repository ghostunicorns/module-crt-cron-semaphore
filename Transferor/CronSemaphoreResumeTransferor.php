<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCronSemaphore\Transferor;

use Exception;
use GhostUnicorns\CronSemaphore\Api\CronSempahoreManagerInterface;
use GhostUnicorns\CrtBase\Api\TransferorInterface;
use GhostUnicorns\CrtBase\Exception\CrtException;
use Monolog\Logger;

class CronSemaphoreResumeTransferor implements TransferorInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CronSempahoreManagerInterface
     */
    private $cronSempahoreManager;

    /**
     * @param Logger $logger
     * @param CronSempahoreManagerInterface $cronSempahoreManager
     */
    public function __construct(
        Logger $logger,
        CronSempahoreManagerInterface $cronSempahoreManager
    ) {
        $this->logger = $logger;
        $this->cronSempahoreManager = $cronSempahoreManager;
    }

    /**
     * @param int $activityId
     * @param string $transferorType
     * @throws CrtException
     */
    public function execute(int $activityId, string $transferorType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Transferor ~ transferorType:%2 ~ START',
            $activityId,
            $transferorType
        ));

        try {
            $this->cronSempahoreManager->resume();
        } catch (Exception $e) {
            throw new CrtException(__(
                'activityId:%1 ~ Transferor ~ transferorType:%2 ~ ERROR',
                $activityId,
                $transferorType
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Transferor ~ transferorType:%2 ~ END',
            $activityId,
            $transferorType
        ));
    }
}
