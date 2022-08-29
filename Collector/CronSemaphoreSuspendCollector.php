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

class CronSemaphoreSuspendCollector implements CollectorInterface
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
     * @var int
     */
    private $forSeconds;

    /**
     * @param Logger $logger
     * @param CronSempahoreManager $cronSempahoreManager
     * @param int $forSeconds
     */
    public function __construct(
        Logger $logger,
        CronSempahoreManager $cronSempahoreManager,
        int $forSeconds = CronSempahoreManager::TIMEOUT_EXPIRE
    ) {
        $this->logger = $logger;
        $this->cronSempahoreManager = $cronSempahoreManager;
        $this->forSeconds = $forSeconds;
    }

    /**
     * @param int $activityId
     * @param string $collectorType
     * @throws CrtException
     */
    public function execute(int $activityId, string $collectorType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Collector ~ collectorType:%2 ~ forSeconds limit:%3 ~ START',
            $activityId,
            $collectorType,
            $this->forSeconds
        ));

        try {
            $this->cronSempahoreManager->suspend($this->forSeconds);
        } catch (Exception $e) {
            throw new CrtException(__(
                'activityId:%1 ~ Collector ~ collectorType:%2 ~ forSeconds limit:%3 ~ ERROR',
                $activityId,
                $collectorType,
                $this->forSeconds
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Collector ~ collectorType:%2 ~ forSeconds limit:%3 ~ END',
            $activityId,
            $collectorType,
            $this->forSeconds
        ));
    }
}
