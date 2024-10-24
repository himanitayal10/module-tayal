<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Himani\Tayal\Api;

use Symfony\Component\Console\Input\InputInterface;

interface CustomerInterface
{
    public const PROFILE_NAME = "profile";
    public const FILE_PATH = "filepath";

    /**
     * Get Import Customer
     *
     * @param InputInterface $input
     * @return array
     */
    public function getImportData(InputInterface $input): array;

    /**
     * Read Customer
     *
     * @param string $data
     * @return array
     */
    public function readData(string $data): array;

    /**
     * Format Customer Data
     *
     * @param mixed $data
     * @return array
     */
    public function formatData($data): array;
}
