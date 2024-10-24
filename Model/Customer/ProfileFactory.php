<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Himani\Tayal\Model\Customer;

use Himani\Tayal\Api\CustomerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Exception\AlreadyExistsException;

class ProfileFactory
{
    /**
     * Interface ObjectManager
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Type
     */
    protected $type;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param string $type
     * @throws \Exception
     */
    public function create(string $type): CustomerInterface
    {

        if ($type === "csv") {
            $class = CsvImporter::class;
        } elseif ($type === "json") {
            $class = JsonImporter::class;
        } else {
            throw new \AlreadyExistsException(__("Unsupported Profile type specified"));
        }
        return $this->objectManager->create($class);
    }
}
