<?php
namespace Isendu\ConnectorModule\Observer\Sales;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;

class OrderShipmentSaveAfter implements ObserverInterface
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $shipment = $observer->getEvent()->getShipment();
            /** @var \Magento\Sales\Model\Order $order */
            $order = $shipment->getOrder();
            $this->logger->debug('Inviata notifica ordine: '.$order->getIncrementId());
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }


    }
}
