<?php

namespace Omnipay\DanskeBank\Message;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'description',
            'returnUrl',
            'cancelUrl',
            'notifyUrl'
        );

        // API needs an integer, so generate random integer, field max allowed length is 20
        $id = (int) hrtime(true);

        $request = [
            'lng' => $this->getLng($this->getLanguage()),
            'ALG' => '03',
            'VERSIO' => '4',
            'KNRO' => $this->getServiceProvidersId(),
            'VALUUTTA' => $this->getCurrency(),
            'SUMMA' => $this->getAmount(),
            'VIITE' => $this->generateReferenceNumber($id),
            'ERAPAIVA' => date('d.m.Y'),
            'OKURL' => $this->getReturnUrl(),
            'VIRHEURL' => $this->getCancelUrl(),
        ];

        $request['TARKISTE'] = $this->createVerificationCode($request);

        return [
            'id' => $id,
            'url' => $this->getEndpoint(),
            'data' => $request,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    /**
     * Convert ISO-639-1 language code to lng.
     *
     * @param string $language ISO-639-1 language code
     *
     * @return string lng
     */
    protected function getLng(string $language)
    {
        $languages = [
            'fi' => '1',
            'se' => '2',
            'en' => '3',
        ];

        $language = strtolower($language);

        if (array_key_exists($language, $languages)) {
            return strtoupper($languages[$language]);
        }

        return '3';
    }

    /**
     * http://www.sampopankki.fi/en-fi/Corporate/SmallBusinesses/Payments/IncomingPayments/ReferenceService/Pages/referencenumbercalculatorEN.aspx
     * @return string
     */
    protected function generateReferenceNumber($number, $prefix = "21")
    {
        $number = $prefix.$number; // min length 3 - add some prefix so that the number could start from 1
        $method = 7;
        $control_summ = 0;
        for($i = strlen($number) - 1; $i >= 0; $i--) {
            $control_summ = $control_summ + ($number[$i] * $method);
            if($method == 7) {
                $method = 3;
            } elseif($method == 3) {
                $method = 1;
            } elseif($method == 1) {
                $method = 7;
            }
        }
        $last_zero_digi = $control_summ + (10 - (int)substr($control_summ, (int)strlen($control_summ) - 1));
        $control_digi = $last_zero_digi - $control_summ;
        if($control_digi == 10) {
            $control_digi = 0;
        }
        return $number.$control_digi;
    }

    /**
     * VERIFICATION CODE = SHA256(MAC+’&’+SUMMA+’&’+VIITE+’&’+KNRO+’&’+VERSIO+’&’+VALUUTTA+’&’+OKURL+’&’+VIRHEURL+’&’+ ERAPAIVA+’&’)
     * @param array $data
     * @return string
     */
    protected function createVerificationCode(array $data)
    {
        return hash('sha256', $this->getServiceProviderMac()."&".$data['SUMMA']."&".$data['VIITE']."&".$data['KNRO']."&".$data['VERSIO']."&".$data['VALUUTTA']."&".$data['OKURL']."&".$data['VIRHEURL']."&".$data['ERAPAIVA']."&");
    }
}
