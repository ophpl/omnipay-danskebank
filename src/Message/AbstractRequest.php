<?php

namespace Omnipay\DanskeBank\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getEndpoint()
    {
        return 'https://verkkopankki.danskebank.fi/SP/vemaha/VemahaApp';
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
     * Get locale.
     *
     * @return string locale
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Set locale.
     *
     * @param string $value locale
     *
     * @return $this
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get language, if not set fallback to locale.
     *
     * @return string language
     */
    public function getLanguage()
    {
        $language = $this->getParameter('language');

        if (empty($language)) {
            $locale = $this->getLocale();

            if (empty($locale)) {
                return "";
            }

            // convert to IETF locale tag if other style is provided and then get first part, primary language
            $language = strtok(str_replace('_', '-', $locale), '-');
        }

        return strtoupper($language);
    }

    /**
     * Set language.
     *
     * @param string $value language
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }
}
