<?php

namespace Isendu\ConnectorModule\Model;

use Isendu\ConnectorModule\Api\ItemRepositoryInterface;
use Isendu\ConnectorModule\Model\ResourceModel\Item\CollectionFactory;
use Isendu\ConnectorModule\Model\ItemFactory;
use Isendu\ConnectorModule\Model\Config;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderRepository;
use Psr\Log\LoggerInterface;


class ItemRepository implements ItemRepositoryInterface
{
    private $collectionFactory;
    private $_objectManager;
    private $orderRepository;
    private $itemFactory;
    private $config;
    private $logger;

    public function __construct(LoggerInterface $logger, Config $config, ItemFactory $itemFactory, OrderRepository $orderRepository, CollectionFactory $collectionFactory, ObjectManagerInterface $objectManager)
    {
        $this->collectionFactory = $collectionFactory;
        $this->_objectManager = $objectManager;
        $this->orderRepository = $orderRepository;
        $this->itemFactory = $itemFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function getList()
    {
        if($this->config->isEnabled()) {
            $this->logger->debug('getList');
            return $this->collectionFactory->create()->getItems();
        }
    }

    public function customPostMethod($isendu_id,$order_id,$track_url,$carrier, $notification)
    {

        if($this->config->isEnabled()) {
            $this->logger->debug('customPostMethod');
            try {

                $order = $this->_objectManager->create('\Magento\Sales\Model\Order')->load($order_id, 'increment_id');

                if (!$order->canShip()) {
                    $this->logger->debug('You can\'t create an shipment for order increment_id '.$order_id);
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('You can\'t create an shipment.')
                    );
                }

                $convertOrder = $this->_objectManager->create('Magento\Sales\Model\Convert\Order');
                $shipment = $convertOrder->toShipment($order);

                foreach ($order->getAllItems() AS $orderItem) {

                    if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                        continue;
                    }

                    $qtyShipped = $orderItem->getQtyToShip();

                    $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

                    $shipment->addItem($shipmentItem);
                }

                $shipment->register();

                $data = array(
                    'carrier_code' => 'Custom',
                    'title' => $carrier,
                    'number' => $track_url,
                );


                $shipment->getOrder()->setIsInProcess(true);


                $item = $this->itemFactory->create()->load($order_id, 'order_id');
                try {

                    $track = $this->_objectManager->create('Magento\Sales\Model\Order\Shipment\TrackFactory')->create()->addData($data);
                    $shipment->addTrack($track)->save();
                    $shipment->save();
                    $shipment->getOrder()->save();

                    if ($notification) {
                        $this->_objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
                            ->notify($shipment);
                        $item->setNotification('True');
                    } else {
                        $item->setNotification('False');
                    }

                    $shipment->save();


                    try {
                        $item->setIsenduId($isendu_id);
                        $item->setTrackUrl($track_url);
                        $item->setCarrier($carrier);
                        $item->setDateUpdated(date("Y-m-d H:i:s"));
                        $item->setIsObjectNew(false);
                        $item->save();


                    } catch (\Exeption $e) {
                        $this->logger->debug('Error -> '.json_encode($e->getMessage()));
                        $response = ['error' => $e->getMessage()];
                    }


                } catch (\Exception $e) {
                    $this->logger->debug('Error -> '.json_encode($e->getMessage()));
                    $response = ['error' => $e->getMessage()];
                }
                $this->logger->debug('Dati arrivati -> '.json_encode([$isendu_id,$order_id,$track_url,$carrier, $notification]));
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

    /*private function _createShipment($shipment, $itemsQty)
    {
        $itemsQtyArr = array();
        foreach ($itemsQty as $item)
        {
            $itemsQtyArr[$item->iExternalOrderId] = $item->dQtyShipped;
        }

        try
        {
            $shipmentIncrementId = Mage::getModel('sales/order_shipment_api')->create($shipment->sOrderNumber, $itemsQtyArr, $shipment->sShipmentComment, true, true);

            if ($shipmentIncrementId)
            {
                Mage::getModel('sales/order_shipment_api')->addTrack($shipmentIncrementId, $shipment->sCarrierCode, $shipment->sTrackingTitle, $shipment->sTrackingNumber);
            }
        }
        catch(Exception $e)
        {
            Mage::log('Exception: ' . $e->getMessage());
        }

        return $shipmentIncrementId ? true : false;
    }*/
}
