<?php

namespace Interteleco\Smsbox\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $tableName = $installer->getTable('interteleco_smsbox');

        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'order_id',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'Order Id'
                ) ->addColumn(
                    'sender',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Sender'
                )
                ->addColumn(
                    'number',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Number'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Message'
                )
                ->addColumn(
                    'sent_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Sent At'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Status'
                )->addColumn(
                    'type',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Type'
                )->addColumn(
                    'response',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Response'
                )
                ->setComment('Smsbox Table')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
