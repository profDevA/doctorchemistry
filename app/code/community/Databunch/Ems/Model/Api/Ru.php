<?php
class Databunch_Ems_Model_Api_Ru
{
    /** @var  string */
    protected $_apiUri;
    /** @var  Zend_Http_Client */
    protected $_httpClient;

    const REST_METHOD_GET_SERVICE_STATUS = 'ems.test.echo';
    const REST_METHOD_GET_LOCATIONS      = 'ems.get.locations';
    const REST_METHOD_GET_MAX_WEIGHT     = 'ems.get.max.weight';
    const REST_METHOD_CALCULATE          = 'ems.calculate';

    /**
     * @param null|string $uri
     */
    public function __construct($uri = null)
    {
        if (!empty($uri)) {
            $this->setUri($uri);
        }
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        if (!Zend_Uri::factory($uri)) {
            Mage::throwException('Invalid API uri.');
        }
        $this->_apiUri = $uri;

        return $this;
    }

    /**
     * @return Zend_Http_Client
     */
    protected function _getClient()
    {
        if (is_null($this->_httpClient)) {
            if (!$this->_apiUri) {
                Mage::throwException('Please specify API uri.');
            }
            $this->_httpClient = new Zend_Http_Client($this->_apiUri);
        }

        return $this->_httpClient->resetParameters(true);
    }

    /**
     * @param array $parameters
     * @param string $method
     * @return bool|array
     */
    public function makeRequest($parameters, $method = Zend_Http_Client::GET)
    {
        try {
            $client = $this->_getClient();

            if (Zend_Http_Client::POST == $method) {
                $client->setParameterPost($parameters);
            } else {
                $client->setParameterGet($parameters);
            }

            $response = $client->request($method);

            if (200 == $response->getStatus()) {
                $response = Zend_Json::decode($response->getBody());
                if (is_array($response) && isset($response['rsp'], $response['rsp']['stat'])) {
                    if ('ok' == $response['rsp']['stat']) {
                        return $response['rsp'];
                    } else {
                        if (isset($response['rsp']['err']['msg'])) {
                            $msg = $response['rsp']['err']['msg'];
                        } else {
                            $msg = 'Server returned unknown error.';
                        }
                        Mage::throwException('EMS Post API Error: ' . $msg);
                    }
                } else {
                    Mage::throwException('EMS Post API Error: Incorrect response from server');
                }
            } else {
                Mage::throwException('EMS Post API Error: Response Status = '.$response->getStatus());
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        $response = $this->makeRequest(array(
            'method' => self::REST_METHOD_GET_SERVICE_STATUS
        ));

        if ($response && isset($response['stat'], $response['msg'])) {
            if ('ok' == $response['stat'] && 'successeful' == $response['msg']) {
                return true;
            }
        }

        return false;
    }
}