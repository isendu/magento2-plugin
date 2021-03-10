<?php
namespace Isendu\ConnectorModule\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->insert(
            $setup->getTable('isendu_connector_config'),
            [
                'token' => 'mrTr8YASejQdJQ6SXjmUmkv5A5fhJuwNfakPnjHg9kWAdgkDKQcyxLN4byCuXqJ3',
                'url' => 'https://magento.test/apitest.php'
            ]
        );


        $setup->endSetup();
    }
}
