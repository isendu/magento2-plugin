<?php
namespace Isendu\ConnectorModule\Observer\Sales;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Isendu\ConnectorModule\Model\ItemFactory;
use Isendu\ConnectorModule\Model\ItemIsenduFactory;
use Isendu\ConnectorModule\Model\ResourceModel\ItemIsendu\CollectionFactory;
use Magento\Framework\ObjectManagerInterface;
use Isendu\ConnectorModule\Model\Config;
use Isendu\ConnectorModule\Api\ItemIsenduRepositoryInterface;

class OrderPlaceAfter implements ObserverInterface
{

    private $logger;
    private $itemFactory;
    private $_objectManager;
    private $config;
    private $itemIsenduFactory;
    private $collectionFactory;

    public function __construct(Config $config, LoggerInterface $logger, ItemFactory $itemFactory, ObjectManagerInterface $objectManager, ItemIsenduFactory $itemIsenduFactory, CollectionFactory $collectionFactory)
    {
        $this->logger = $logger;
        $this->itemFactory = $itemFactory;
        $this->config = $config;
        $this->_objectManager = $objectManager;
        $this->itemIsenduFactory = $itemIsenduFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute(Observer $observer)
    {
        if($this->config->isEnabled()) {
            try {
                $order = $observer->getEvent()->getOrder();

                $item = $this->itemFactory->create();
                $item->setIsenduId();
                $item->setOrderId($order->getIncrementId());
                $item->setTrackUrl();
                $item->setCarrier();
                $item->setNotification();
                $item->setDateCreated(date("Y-m-d H:i:s"));
                $item->setDateUpdated();
                $item->setIsObjectNew(true);
                $item->save();

                //SET CURL ISENDU
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
                $base = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $itemIsendu = $this->collectionFactory->create()->getItems();
                $setupIsendu = current($itemIsendu);

                $dati = $order->getData();
                $dati['store_path'] = $base;
                $arr = [];
                foreach($order->getItems() as $item) {
                    $arr[] = $item->getData();
                }
                $dati['items'] = $arr;
                $dati['billing_address']=$order->getBillingAddress()->getData();
                $dati['payment']=$order->getPayment()->getData();

                $harr = [];
                foreach($order->getStatusHistories() as $histories) {
                    $harr[] = $histories->getData();
                }
                $dati['status_histories'] = $harr;

                $earr = [];
                foreach($order->getExtensionAttributes() as $ex) {
                    $earr[] = $ex->getData();
                }
                $dati['extension_attributes'] = $earr;

                $data = ['result'=>$dati];
                $this->logger->debug('before -> order IncrementId #'.$order->getIncrementId());
                $data_string = json_encode($data);
                $authorization = "Authorization: Bearer ".$setupIsendu->getToken();
                $ch = curl_init($setupIsendu->getUrl());
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        $authorization,
                        'Content-Length: ' . strlen($data_string))
                );

                $result = curl_exec($ch);
                $this->logger->debug('after -> '.json_encode($result));
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());
            }

        }

    }
}
