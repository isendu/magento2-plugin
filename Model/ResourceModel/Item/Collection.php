<?php
namespace Isendu\ConnectorModule\Model\ResourceModel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Isendu\ConnectorModule\Model\Item;
use Isendu\ConnectorModule\Model\ResourceModel\Item as ItemResource;


class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Item::class, ItemResource::class);
    }
}
