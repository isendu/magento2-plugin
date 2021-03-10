<?php

namespace Isendu\ConnectorModule\Model;

use Isendu\ConnectorModule\Api\ItemIsenduRepositoryInterface;
use Isendu\ConnectorModule\Model\ResourceModel\ItemIsendu\CollectionFactory;
use Isendu\ConnectorModule\Model\ItemIsenduFactory;
use Isendu\ConnectorModule\Model\Config as Configura;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderRepository;
use Psr\Log\LoggerInterface;


class ItemIsenduRepository implements ItemIsenduRepositoryInterface
{
    private $collectionFactory;
    private $_objectManager;
    private $orderRepository;
    private $itemFactory;
    private $config;
    private $logger;

    public function __construct(LoggerInterface $logger, Configura $config, ItemIsenduFactory $itemFactory, OrderRepository $orderRepository, CollectionFactory $collectionFactory, ObjectManagerInterface $objectManager)
    {
        $this->collectionFactory = $collectionFactory;
        $this->_objectManager = $objectManager;
        $this->orderRepository = $orderRepository;
        $this->itemFactory = $itemFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function getSetup()
    {
        if($this->config->isEnabled()) {
            $this->logger->debug('getSetup');
            return $this->collectionFactory->create()->getItems();
        }
    }

    public function setupMethod($token,$url, $id)
    {

        if($this->config->isEnabled()) {
            $this->logger->debug('setupMethod');
            try {
                $item = $this->itemFactory->create()->load($id, 'id');
                $item->setToken($token);
                $item->setUrl($url);

                $item->setIsObjectNew(false);
                $item->save();
                $response = [
                    'success' => 'ok',
                ];
            } catch (\Exception $e) {
                $this->logger->debug('Error -> '.json_encode($e->getMessage()));
                $response = ['error' => $e->getMessage()];
            }
            return json_encode($response);
        }
    }

}
