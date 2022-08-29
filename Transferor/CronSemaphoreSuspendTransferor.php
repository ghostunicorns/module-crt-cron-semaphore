<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCronSemaphore\Transferor;

use Exception;
use GhostUnicorns\CrtCronSemaphore\Model\CronSempahoreManager;
use GhostUnicorns\CrtBase\Api\TransferorInterface;
use GhostUnicorns\CrtBase\Exception\CrtException;
use Monolog\Logger;

class CronSemaphoreSuspendTransferor implements TransferorInterface
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
     * @param string $transferorType
     * @throws CrtException
     */
    public function execute(int $activityId, string $transferorType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Transferor ~ transferorType:%2 ~ forSeconds limit:%3 ~ START',
            $activityId,
            $transferorType,
            $this->forSeconds
        ));

        try {
            $this->cronSempahoreManager->suspend($this->forSeconds);
        } catch (Exception $e) {
            throw new CrtException(__(
                'activityId:%1 ~ Transferor ~ transferorType:%2 ~ forSeconds limit:%3 ~ ERROR',
                $activityId,
                $transferorType,
                $this->forSeconds
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Transferor ~ transferorType:%2 ~ forSeconds limit:%3 ~ END',
            $activityId,
            $transferorType,
            $this->forSeconds
        ));
    }
}
