<?php

namespace SR\Cardcom\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Curl implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $data = $transferObject->getBody();
        $log = [
            'request' => $data,
            'client' => static::class
        ];
        $response['object'] = [];

        try {
            //@todo: implement request sending using CURL


            // start: TEMP solution. USE CURL ADAPTERS
            $curl = curl_init();

            if (!isset($data['api_endpoint'])) {
                throw new \Exception('API Endpoint Urls is required');
            }
            $apiEndPoint = $data['api_endpoint'];
            unset($data['api_endpoint']);

            curl_setopt($curl, CURLOPT_URL, $apiEndPoint);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);

            $urlencoded = http_build_query($data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $urlencoded);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);

            $result = curl_exec($curl);
            // end: TEMP solution

            $response['object'] = $result;
        } catch (\Exception $e) {
            $message = __($e->getMessage() ?: 'Sorry, but something went wrong');
//            $this->logger->critical($message);
            throw new ClientException($message);
        } finally {
            $log['response'] = (array) $response['object'];
//            $this->customLogger->debug($log);
        }

        return $response;
    }
}
