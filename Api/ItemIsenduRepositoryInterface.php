<?php

namespace Isendu\ConnectorModule\Api;

interface ItemIsenduRepositoryInterface
{

    /**
     * @return \Isendu\ConnectorModule\Api\Data\ItemIsenduInterface[]
     */
    public function getSetup();

    /**
     * GET for Post api
     * @param string $token
     * @param string $url
     * @param string $id
     * @return string
     */
    public function setupMethod($token,$url, $id);

}



