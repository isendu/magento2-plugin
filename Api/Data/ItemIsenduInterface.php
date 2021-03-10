<?php

namespace Isendu\ConnectorModule\Api\Data;

interface ItemIsenduInterface
{
    /**
     * @return string|null
     */
    public function getToken();

    /**
     * @return string
     */
    public function getUrl();



}
