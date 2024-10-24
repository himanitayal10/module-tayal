<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Himani\Tayal\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Himani\Tayal\Api\CustomerInterface;
use Himani\Tayal\Model\Customer\ProfileFactory;
use Himani\Tayal\Model\Customer;

class CustomerImport extends Command
{
    /**
     * @var Importer
     */
    protected $importer;

    /**
     * @var ProfileFactory
     */
    protected $profileFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var State
     */
    private $state;

    /**
     * CustomerImport constructor
     *
     * @param ProfileFactory $profileFactory
     * @param Customer $customer
     * @param StoreManagerInterface $storeManager
     * @param FileSystem $filesystem
     * @param State $state
     */
    public function __construct(
        ProfileFactory $profileFactory,
        Customer $customer,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        State $state
    ) {
        parent::__construct();
        
        $this->profileFactory = $profileFactory;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->state = $state;
    }

    /**
     * Configure Action
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName("customer:import");
        $this->setDescription(__("Customer Import via Command"));
        $this->setDefinition(
            [
                new InputArgument(
                    CustomerInterface::PROFILE_NAME,
                    InputArgument::REQUIRED,
                    __("Profile name ex: sample-csv")
                ),
                new InputArgument(
                    CustomerInterface::FILE_PATH,
                    InputArgument::REQUIRED,
                    __("File Path ex: sample.csv")
                )
            ]
        );
        parent::configure();
    }

    /**
     * Execute Action
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $profileType = $input->getArgument(CustomerInterface::PROFILE_NAME);
        $filePath = $input->getArgument(CustomerInterface::FILE_PATH);
        $output->writeln(sprintf(__("Profile type: %s"), $profileType));
        $output->writeln(sprintf(__("File Path: %s"), $filePath));

        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);

            if ($importData = $this->getImporterInstance($profileType)->getImportData($input)) {
                $storeId = $this->storeManager->getStore()->getId();
                $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
                
                foreach ($importData as $data) {
                    $this->customer->createCustomer($data, $websiteId, $storeId);
                }

                $output->writeln(sprintf(__("Total of %s Customers are imported"), count($importData)));
                return Cli::RETURN_SUCCESS;
            }

            return Cli::RETURN_FAILURE;
   
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $output->writeln(__("<error>$msg</error>"), OutputInterface::OUTPUT_NORMAL);
            return Cli::RETURN_FAILURE;
        }
    }

    /**
     * Get Importer Instance
     *
     * @param array $profileType
     * @return CustomerInterface
     */
    protected function getImporterInstance($profileType): CustomerInterface
    {
        if (!($this->importer instanceof CustomerInterface)) {
            $this->importer = $this->profileFactory->create($profileType);
        }
        return $this->importer;
    }
}
