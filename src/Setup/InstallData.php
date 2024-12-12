<?php
namespace ReadyMage\HyvaThemeSelect\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Theme\Model\ResourceModel\Theme\CollectionFactory as ThemeCollectionFactory;
use Psr\Log\LoggerInterface;

class InstallData implements InstallDataInterface
{
    protected $storeManager;
    protected $configWriter;
    protected $themeCollectionFactory;
    protected $logger;

    public function __construct(
        StoreManagerInterface $storeManager,
        WriterInterface $configWriter,
        ThemeCollectionFactory $themeCollectionFactory,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->configWriter = $configWriter;
        $this->themeCollectionFactory = $themeCollectionFactory;
        $this->logger = $logger;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Check if the Hyva/default theme exists
        $themeCollection = $this->themeCollectionFactory->create();
        $theme = $themeCollection->getThemeByFullPath('frontend/Hyva/default');

        if ($theme && $theme->getId()) {
            $store = $this->storeManager->getStore();
            $storeId = $store->getId();

            // Set the default theme for the main store view for default scope and scope_id
            $this->configWriter->save('design/theme/theme_id', $theme->getId());
        }

        $setup->endSetup();
    }
}