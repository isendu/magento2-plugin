<?php
namespace Isendu\ConnectorModule\Model\ResourceModel\ItemIsendu;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Isendu\ConnectorModule\Model\ItemIsendu;
use Isendu\ConnectorModule\Model\ResourceModel\ItemIsendu as ItemIsenduResource;


class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(ItemIsendu::class, ItemIsenduResource::class);
    }
}
