<?php
namespace Isendu\ConnectorModule\Model;

use Magento\Framework\Model\AbstractModel;

class ItemIsendu extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Isendu\ConnectorModule\Model\ResourceModel\ItemIsendu::class);
    }
}
