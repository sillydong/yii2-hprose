<?php
/**
 * User: chenzhidong
 * Date: 16/11/17
 */
namespace sillydong\hprose;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use Exception;
use Hprose\Socket\Client;

class Service extends Component {
    /**
     * @var bool record every call and time usage
     */
    public $debug = false;
    /**
     * @var array|string server urls
     */
    public $urls;
    /**
     * @var int timeout
     */
    public $timeout = 1000; //ms
    /**
     * @var int max retry times
     */
    public $retry = 10;
    /**
     * @var bool idempotent
     */
    public $idempotent = false;
    /**
     * @var bool failswitch
     */
    public $failswitch = false;
    /**
     * @var bool full duplex
     */
    public $fullDuplex = false;
    /**
     * @var bool keepalive
     */
    public $keepAlive = true;
    /**
     * @var null extra options
     */
    public $options = null;

    /**
     * @var Client hprose client
     */
    protected $client;

    public function init()
    {
        if ($this->client == null) {
            if (empty($this->urls)) {
                throw new InvalidConfigException('urls empty');
            }
            $this->client = new Client($this->urls, false);
            $this->client->noDelay = true;
            $this->client->fullDuplex = $this->fullDuplex;
            $this->client->failswitch = $this->failswitch;
            $this->client->timeout = $this->timeout;
            $this->client->idempotent = $this->idempotent;
            $this->client->retry = $this->retry;
            $this->client->keepAlive = $this->keepAlive;
            $this->client->options = $this->options;
        }
    }

    public function __call($name, $params)
    {
        try {
            if ($this->debug) {
                $start = microtime(true);
                $result = call_user_func_array(array(&$this->client, $name), $params);
                $stop = microtime(true);
                Yii::info(sprintf('[%s]timeusage: %f,name: %s, params: %s', $this->client->uri, $stop - $start, $name, json_encode($params)), 'hprose');
            } else {
                $result = call_user_func_array(array(&$this->client, $name), $params);
            }

            return $result;
        } catch (Exception $e) {
            throw new InvalidConfigException($this->client->uri . " for $name is not available: ".$e->getMessage());
        }
    }
}
