<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Himani\Tayal\Model;
 
use Magento\Framework\Exception;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Himani\Tayal\Model\Import\CustomerImport;
 
class Customer
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var StoreManagerInterface
     */
    private $storeManagerInterface;

    /**
     * @var CustomerImport
     */
    private $customerImport;

    /**
     * @var Output
     */
    private $output;
    
    /**
     * @param File $file
     * @param StoreManagerInterface $storeManagerInterface
     * @param CustomerImport $customerImport
     */
    public function __construct(
        File $file,
        StoreManagerInterface $storeManagerInterface,
        CustomerImport $customerImport
    ) {
        $this->file = $file;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->customerImport = $customerImport;
    }

    /**
     * Create Customer by CSV
     *
     * @param array $data
     * @param int $websiteId
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createCustomer(array $data, int $websiteId, int $storeId): void
    {
        try {
            $customerData = [
                'email'         => $data['emailaddress'],
                '_website'      => 'base',
                '_store'        => 'default',
                'confirmation'  => null,
                'dob'           => null,
                'firstname'     => $data['fname'],
                'gender'        => null,
                'group_id'      => 1,
                'lastname'      => $data['lname'],
                'middlename'    => null,
                'password_hash' => $data['password_hash'],
                'prefix'        => null,
                'store_id'      => $storeId,
                'website_id'    => $websiteId,
                'password'      => null,
                'disable_auto_group_change' => 0
            ];
            
            $this->customerImport->importCustomerData($customerData);
        } catch (Exception $e) {
            $this->output->writeln(
                '<error>'. $e->getMessage() .'</error>',
                OutputInterface::OUTPUT_NORMAL
            );
        }
    }
}
