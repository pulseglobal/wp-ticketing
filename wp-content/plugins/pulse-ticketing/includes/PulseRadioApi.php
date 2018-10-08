<?php

/**
 * PulseRadio API Class
 */
class PulseRadioApi
{
    protected $apiKey;
    protected $endpoint;

    /**
     * PulseRadioApi constructor.
     * @param $apiKey
     */
    public function __construct($apiKey, $endpoint)
    {
        $this->apiKey = $apiKey;
	    $this->endpoint = $endpoint;
    }

    /**
     * @param $eventId
     * @return array
     */
    public function getTickets($eventId)
    {
        return $this->sendRequest(sprintf('/events/%d/tickets', $eventId), 'GET');
    }

    /**
     * @param $order
     * @return array
     */
    public function placeOrder($order)
    {
        return $this->sendRequest(sprintf('/orders'), 'POST', $order);
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function getOrder($orderNumber)
    {
        return $this->sendRequest(sprintf('/orders/%d', $orderNumber), 'GET');
    }

    /**
     * @param $resource
     * @param string $method
     * @param null $data
     * @return array
     * @throws Exception
     */
    protected function sendRequest($resource, $method = 'GET', $data = null)
    {
        $options = array(
            'http' => array(
                'method'  => strtoupper($method),
                'ignore_errors' => true
            )
        );
        if (!empty($data)) {
            $options['http']['content'] = json_encode($data);
        }
        if ($method != 'GET') {
            $options['http']['header'] = 'Content-Type: application/json; charset=utf-8';
        }
        $context  = stream_context_create($options);
        $result = file_get_contents($this->endpoint . $resource . '.json?api_key=' . $this->apiKey, false, $context);
        if ($result === FALSE) {
            throw new Exception('Request failed');
        }

        $decodedResult = json_decode($result, true);
        return $decodedResult['data'];
    }
}