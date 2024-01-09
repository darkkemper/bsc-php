<?php

namespace Binance;

class BscscanApi implements ProxyApi
{
    protected $apiKey;
    protected $network;

    function __construct(string $apiKey, $network = 'mainnet')
    {
        $this->apiKey = $apiKey;
        $this->network = $network;
    }

    public function send($method, $params = [], $error_reporting = false)
    {
        $defaultParams = [
            'module' => 'proxy',
            'tag' => 'latest',
        ];

        isset($params['params']) ? $params = $params['params'] : null;

        foreach ($defaultParams as $key => $val) {
            if (!isset($params[$key])) {
                $params[$key] = $val;
            }
        }

        $preApi = 'api';
        if ($this->network != 'mainnet') {
            $preApi .= '-' . $this->network;
        }

        $url = "https://$preApi.bscscan.com/api?action={$method}&apikey={$this->apiKey}";
        if ($params && count($params) > 0) {
            $strParams = http_build_query($params);
            $url .= "&{$strParams}";
        }

        if (!$error_reporting) {
            $response = Utils::httpRequest('GET', $url);
            if (isset($response['result'])) {
                return $response['result'];
            } else {
                return false;
            }
        } else {
            try {
                $scheme = [
                    'result' => null,
                    'error' => null
                ];

                $response = Utils::httpRequest('GET', $url);

                return Utils::array_merge_recursive_distinct($scheme, $response);
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }
    }

    function gasPrice()
    {
        return $this->send('eth_gasPrice');
    }

    function bnbBalance(string $address)
    {
        $params['module'] = 'account';
        $params['address'] = $address;

        $retDiv = Utils::fromWei($this->send('balance', $params), 'ether');
        if (is_array($retDiv)) {
            return Utils::divideDisplay($retDiv, 18);
        } else {
            return $retDiv;
        }
    }

    function receiptStatus(string $txHash): ?bool
    {
        $res = $this->send('eth_getTransactionByHash', ['txhash' => $txHash]);
        if (!$res) {
            return false;
        }

        if (!$res['blockNumber']) {
            return null;
        }

        $params['module'] = 'transaction';
        $params['txhash'] = $txHash;

        $res =  $this->send('gettxreceiptstatus', $params);
        return $res['status'] == '1';
    }

    function getTransactionReceipt(string $txHash)
    {
        return $this->send('eth_getTransactionReceipt', ['txhash' => $txHash], true);
    }

    function getTransactionByHash(string $txHash)
    {
        return $this->send('eth_getTransactionByHash', ['txHash' => $txHash]);
    }

    function sendRawTransaction($raw)
    {
        return $this->send('eth_sendRawTransaction', ['hex' => $raw], true);
    }

    function getNonce(string $address)
    {
        return $this->send('eth_getTransactionCount', ['address' => $address]);
    }

    function getNetwork(): string
    {
        return $this->network;
    }

    function ethCall($params): string
    {
        return $this->send('eth_call', ['params' => $params, 'latest']);
    }

    function blockNumber()
    {
        return hexdec($this->send('eth_blockNumber'));
    }

    function getBlockByNumber(int $blockNumber)
    {
        $blockNumber = Utils::toHex($blockNumber, true);
        return $this->send('eth_getBlockByNumber', ['blockNumber' => $blockNumber, true]);
    }
}
