<?php

namespace Isendu\ConnectorModule\Api;

interface ItemRepositoryInterface
{

    /**
     * @return \Isendu\ConnectorModule\Api\Data\ItemInterface[]
     */
    public function getList();

    /**
     * GET for Post api
     * @param string $isendu_id
     * @param string $order_id
     * @param string $track_url
     * @param string $carrier
     * @param string $notification
     * @return string
     */
    public function customPostMethod($isendu_id,$order_id,$track_url,$carrier, $notification);

}



