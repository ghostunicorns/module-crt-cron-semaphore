<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCronSemaphore\Collector;

use Exception;
use GhostUnicorns\CrtCronSemaphore\Model\CronSempahoreManager;
use GhostUnicorns\CrtBase\Api\CollectorInterface;
use GhostUnicorns\CrtBase\Exception\CrtException;
use Monolog\Logger;

class CronSemaphoreResumeCollector implements CollectorInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CronSempahoreManager
     */
    private $cronSempahoreManager;

    /**
     * @param Logger $logger
     * @param CronSempahoreManager $cronSempahoreManager
     */
    public function __construct(
        Logger $logger,
        CronSempahoreManager $cronSempahoreManager
    ) {
        $this->logger = $logger;
        $this->cronSempahoreManager = $cronSempahoreManager;
    }

    /**
     * @param int $activityId
     * @param string $collectorType
     * @throws CrtException
     */
    public function execute(int $activityId, string $collectorType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Collector ~ collectorType:%2 ~ START',
            $activityId,
            $collectorType
        ));

        try {
            $this->cronSempahoreManager->resume();
        } catch (Exception $e) {
            throw new CrtException(__(
                'activityId:%1 ~ Collector ~ collectorType:%2 ~ ERROR',
                $activityId,
                $collectorType
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Collector ~ collectorType:%2 ~ END',
            $activityId,
            $collectorType
        ));
    }
}
