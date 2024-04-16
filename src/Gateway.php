<?php

namespace Omnipay\DanskeBank;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Class Gateway
 * https://www.danskebank.fi/PDF/en/Maksuliike/VERKKOMAKSUPALVELU-PALVELUNTARJOAJANOHJEKIRJAUKSP.pdf
 * @package Omnipay\DanskeBank
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Danske Bank';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'serviceProvidersId' => 0,
            'serviceProviderMac' => '',
            'testMode'           => false
        );
    }

    /**
     * Get service providers id.
     *
     * @return string service providers id
     */
    public function getServiceProvidersId()
    {
        return $this->getParameter('serviceProvidersId');
    }

    /**
     * Set service providers id.
     *
     * @param string $value service providers id
     *
     * @return $this
     */
    public function setServiceProvidersId($value)
    {
        return $this->setParameter('serviceProvidersId', $value);
    }

    /**
     * Get merchant secret key.
     *
     * @return string service provider mac
     */
    public function getServiceProviderMac()
    {
        return $this->getParameter('serviceProviderMac');
    }

    /**
     * Set service provider mac.
     *
     * @param string $value service provider mac
     *
     * @return $this
     */
    public function setServiceProviderMac($value)
    {
        return $this->setParameter('serviceProviderMac', $value);
    }

    /**
     * @param array $parameters
     *
     * @return AbstractRequest|PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\DanskeBank\Message\PurchaseRequest', $parameters);
    }
}
