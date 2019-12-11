<?php
namespace Coinremitter;

class Coinremitter {
//    use Config;
    /**
     * 
     * @var string endpoint of api
     */
    private $url='https://coinremitter.com/api/';
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
        if(!function_exists('curl_version')){
            throw new \Exception("php-curl is not enabled. Install it");
        }
        
    }
    /**
     * get balance of specified coin.
     * @return array() returns array with success or error response.
     */
    public function get_balance(){
        $url = $this->url.$this->coin.'/get-balance';
        $res = $this->curl_call($url, $this->param);
        return $res;
    }
    /**
     * get new address for specified coin.
     * @param array() $param pass label Optional, label assign to new address.
     * @return array() returns array with success or error response.
     */
    public function get_new_address($param=[]){
        $url = $this->url.$this->coin.'/get-new-address';
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
        $url = $this->url.$this->coin.'/validate-address';
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

        $url = $this->url.$this->coin.'/withdraw';
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
    
        $url = $this->url.$this->coin.'/get-transaction';
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

        $url = $this->url.$this->coin.'/create-invoice';
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

        $url = $this->url.$this->coin.'/get-invoice';
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
     * 
     * @param string $url
     * @param array $post optional, parameters.
     * @return array()
     */
    public  function curl_call($url, $post = '') {
	
        if(!isset($post['api_key']) && !isset($post['password'])){
            return $this->error_res('Please set API_KEY and PASSWORD for '.$this->coin);
        }
        
        $header[] = "Accept: application/json";
	
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt( $ch, CURLOPT_TIMEOUT, 20);
            
    	if ($post){
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    	}

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    	$rs = curl_exec($ch);
        $info =  curl_getinfo($ch);

            
            
    	if(empty($rs)){
                curl_close($ch);
                return $this->error_res();
    	}
    	curl_close($ch);
        
        $decode = json_decode($rs,true);
        
        
	return $decode;
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
