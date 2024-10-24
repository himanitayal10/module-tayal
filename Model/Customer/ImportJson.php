<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Himani\Tayal\Model\Customer;

use Himani\Tayal\Api\CustomerInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;

class ImportJson implements CustomerInterface
{
    /**
     * @var File
     */
    private $file;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CsvImporter constructor.
     * @param File $file
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        File $file,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->file = $file;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }
    /**
     * @inheritDoc
     */
    public function getImportData(InputInterface $input): array
    {
        $file = $input->getArgument(CustomerInterface::FILE_PATH);
        return $this->readData($file);
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     * @throws Exception
     */
    public function readData(string $file): array
    {
        try {
            if (!$this->file->isExists($file)) {
                throw new LocalizedException(__('Invalid file path or no file found.'));
            }
            $data = $this->file->fileGetContents($file);
            $this->logger->info(__('JSON file is parsed'));
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatData($data);
    }

    /**
     * Serialize or Unserialize Data
     *
     * @param array $data
     */
    public function formatData($data): array
    {
        return $this->serializer->unserialize($data);
    }
}
