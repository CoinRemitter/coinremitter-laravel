<?php
namespace Coinremitter;
use Illuminate\Support\Facades\Http;

class Coinremitter {
//    use Config;
    /**
     * 
     * @var string endpoint of api
     */
    private $url='https://coinremitter.com/api/';
    /**
     * 
     * @var string of api version
     */
    private $version = 'v3';
    /**
     * 
     * @var string of api version
     */
    private $plugin_version = '0.1.9';
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
     * 
     * @param string $coin pass valid coin name like,BTC,LTC
     */
    public function __construct($coin='') {
        $coin = strtoupper($coin);
        
        $this->coin = $coin;
        
        if($coin!= ''){

//            $credentials = $this->get_credentials($coin);
            
            $credentials = config('coinremitter.'.$coin,[]);
            
            if(count($credentials) > 0){
            
                $this->param['api_key']=$credentials['API_KEY'];
            
                $this->param['password']=$credentials['PASSWORD'];
            
            }
        }
        
    }
    /**
     * get balance of specified coin.
     * @return array() returns array with success or error response.
     */
    public function get_balance(){
        $url = $this->url.$this->version.'/'.$this->coin.'/get-balance';
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * get new address for specified coin.
     * @param array() $param pass label Optional, label assign to new address.
     * @return array() returns array with success or error response.
     */
    public function get_new_address($param=[]){
        $url = $this->url.$this->version.'/'.$this->coin.'/get-new-address';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * validate address for specified coin.
     * @param array() $param pass address to verify.
     * @return array() returns array with success or error response.
     */
    public function validate_address($param=[]){
        $url = $this->url.$this->version.'/'.$this->coin.'/validate-address';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * withdraw coin to specific address.
     * @param array() $param pass to_address and amount in array to withdraw amount.
     * @return array() returns array with success or error response.
     */
    public function withdraw($param=[]){

        $url = $this->url.$this->version.'/'.$this->coin.'/withdraw';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * get transaction details of given transaction id.
     * @param array() $param pass id to get transaction detail.
     * @return array() returns array with success or error response.
     */
    public function get_transaction($param=[]){
    
        $url = $this->url.$this->version.'/'.$this->coin.'/get-transaction';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * create invoice for deposit balance.
     * @param array() $param pass parameters in array to generate invoice.
     * @return array() returns array with success or error response.
     */
    public function create_invoice($param=[]){

        $url = $this->url.$this->version.'/'.$this->coin.'/create-invoice';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }    
    /**
     * get invoice details of given invoice id.
     * @param array() $param pass invoice_id to get invoice detail.
     * @return array() returns array with success or error response.
     */
    public function get_invoice($param=[]){

        $url = $this->url.$this->version.'/'.$this->coin.'/get-invoice';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * get all coin usd rate.
     * @return array() returns array with success or error response.
     */
    public function get_coin_rate(){
        $url = $this->url.'get-coin-rate';
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * get crypto rate of given fiat_symbol and fiat_amount.
     * @param array() $param pass fiat_symbol and fiat_amount to get crypto rate.
     * @return array() returns array with success or error response.
     */
    public function get_fiat_to_crypto_rate($param=[]){
        $url = $this->url.$this->version.'/'.$this->coin.'/get-fiat-to-crypto-rate';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * get transaction details  of given address.
     * @param array() $param pass address to get transaction details.
     * @return array() returns array with success or error response.
     */
    public function get_transaction_by_address($param=[]){
        $url = $this->url.$this->version.'/'.$this->coin.'/get-transaction-by-address';
        $this->param = array_merge($this->param,$param);
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * 
     * @param string $url
     * @param array $post optional, parameters.
     * @return array()
     */
    public  function curl_call($url, $post = '') {
    
        if(!isset($post['api_key']) && !isset($post['password'])){
            return $this->error_res('Please set API_KEY and PASSWORD for '.$this->coin);
        }
        $userAgent = 'CR@' . $this->version . ',laravel plugin@'.$this->plugin_version; // 0.1.5
        $header = array('User-Agent' => $userAgent);
        $response = Http::withHeaders($header)->post($url,$post);

        if($response->status() == 200){
            return $response->json();
        }else{
            return $this->error_res();
        }
    }
    /**
     * 
     * @param string $msg optional,message to be return
     * @return array()
     */
    private function error_res($msg =''){
        $res = [
            'flag'=>0,
            'msg'=>'error'
        ];
        if($msg){
            $res['msg']=$msg;
        }
        
        return $res;
    }
}
 