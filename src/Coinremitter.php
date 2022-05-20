<?php

namespace JalalLinuX;

use Illuminate\Support\Facades\Http;

class Coinremitter
{
    /**
     *
     * @var string endpoint of api
     */
    private $url = 'https://coinremitter.com/api/';

    /**
     *
     * @var string of api version
     */
    private $version = 'v3';

    /**
     *
     * @var string of api version
     */
    private $plugin_version = '0.1.10';

    /**
     *
     * @var string  coin for which this api is used.
     */
    private $coin;

    /**
     *
     * @var array() parameters which will be send to api call.
     */
    private $param;

    /**
     * @param string $coin pass valid coin name like,BTC,LTC
     * @param array $credentials
     */
    public function __construct($coin = '', array $credentials = [])
    {
        $coin = strtoupper($coin);
        $this->coin = $coin;

        if ($coin != '') {
            $this->param = $credentials;
        }
    }

    /**
     * get balance of specified coin.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_balance()
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/get-balance';
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * get new address for specified coin.
     * @param array() $param pass label Optional, label assign to new address.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_new_address($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/get-new-address';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * validate address for specified coin.
     * @param array() $param pass address to verify.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function validate_address($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/validate-address';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * withdraw coin to specific address.
     * @param array() $param pass to_address and amount in array to withdraw amount.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function withdraw($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/withdraw';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * get transaction details of given transaction id.
     * @param array() $param pass id to get transaction detail.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_transaction($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/get-transaction';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * create invoice for deposit balance.
     * @param array() $param pass parameters in array to generate invoice.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function create_invoice($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/create-invoice';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * get invoice details of given invoice id.
     * @param array() $param pass invoice_id to get invoice detail.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_invoice($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/get-invoice';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * get all coin usd rate.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_coin_rate()
    {
        $url = $this->url . 'get-coin-rate';
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * get crypto rate of given fiat_symbol and fiat_amount.
     * @param array() $param pass fiat_symbol and fiat_amount to get crypto rate.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_fiat_to_crypto_rate($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/get-fiat-to-crypto-rate';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     * get transaction details  of given address.
     * @param array() $param pass address to get transaction details.
     * @return \Illuminate\Support\Collection returns collection on success and throw exception on error.
     */
    public function get_transaction_by_address($param = [])
    {
        $url = $this->url . $this->version . '/' . $this->coin . '/get-transaction-by-address';
        $this->param = array_merge($this->param, $param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }

    /**
     *
     * @param string $url
     * @param array $post optional, parameters.
     * @return \Illuminate\Support\Collection
     */
    public function curl_call($url, $post = '')
    {
        throw_if(
            !isset($post['api_key']) || !isset($post['password']),
            new \Exception('Credentials not found', 400)
        );

        $userAgent = 'JLCR@' . $this->version . ',laravel plugin@' . $this->plugin_version; // 0.1.10
        $header = array('User-Agent' => $userAgent);
        $response = Http::withHeaders($header)->post($url, $post);

        throw_if(!$response->json('flag'), new \Exception($response->json('msg'), 400));
        return $response->collect('data');
    }
}
