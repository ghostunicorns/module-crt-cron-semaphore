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

class CronSemaphoreResumeTransferor implements TransferorInterface
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
