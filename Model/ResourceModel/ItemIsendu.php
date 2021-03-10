<?php

namespace Isendu\ConnectorModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ItemIsendu extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('isendu_connector_config', 'id');
    }
}
