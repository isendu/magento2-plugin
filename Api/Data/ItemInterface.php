<?php

namespace Isendu\ConnectorModule\Api\Data;

interface ItemInterface
{
    /**
     * @return string|null
     */
    public function getIsenduId();

    /**
     * @return string
     */
    public function getOrderId();

    /**
     * @return string|null
     */
    public function getTrackUrl();

    /**
     * @return string|null
     */
    public function getCarrier();

    /**
     * @return string|null
     */
    public function getNotification();

    /**
     * @return datetime
     */
    public function getDateCreated();

    /**
     * @return datetime|null
     */
    public function getDateUpdated();

}
