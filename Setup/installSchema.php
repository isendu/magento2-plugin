<?php
namespace Isendu\ConnectorModule\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        date_default_timezone_set("Europe/Rome");
        $table = $setup->getConnection()->newTable(
            $setup->getTable('isendu_connector_item')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'isendu_id',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'IsenduId'
        )->addColumn(
            'order_id',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'OrderId'
        )->addColumn(
            'track_url',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'TrackUrl'
        )->addColumn(
            'carrier',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Carrier'
        )->addColumn(
            'notification',
            Table::TYPE_TEXT,
            20,
            ['nullable' => true],
            'Notification'
        )->addColumn(
            'date_created',
            Table::TYPE_DATETIME,
            50,
            ['nullable' => false],
            'DateCreated'
        )->addColumn(
            'date_updated',
            Table::TYPE_DATETIME,
            50,
            ['nullable' => true],
            'DateUpdated'
        )->addIndex(
            $setup->getIdxName('isendu_connector_item', ['name']),
            ['isendu_id']
        )->setComment(
            'Isendu Items'
        );



        $setup->getConnection()->createTable($table);


        $table2 = $setup->getConnection()->newTable(
            $setup->getTable('isendu_connector_config')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Item ID'
        )->addColumn(
            'token',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Token'
        )->addColumn(
        'url',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Url'
    )   ->addIndex(
            $setup->getIdxName('isendu_connector_config', ['name']),
            ['token']
        )->setComment(
            'Config Isendu'
        );

        $setup->getConnection()->createTable($table2);

        $setup->endSetup();
    }
}
