<?php
namespace Coinremitterlaravel\CoinremitterLaravel;

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
     * @param string $label Optional, label assign to new address.
     * @return array() returns array with success or error response.
     */
    public function get_new_address($label=''){
        $url = $this->url.$this->coin.'/get-new-address';
        if($label != ''){
            $this->param['label'] = $label;
        }
        
        $res = $this->curl_call($url, $this->param);
        
        return $res;
    }
    /**
     * validate address for specified coin.
     * @param string $address address to verify.
     * @return array() returns array with success or error response.
     */
    public function validate_address($address){
        $url = $this->url.$this->coin.'/validate-address';
        
        $this->param['address'] = $address;
        
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
        if(!isset($param['to_address'])){
            return $this->error_res('to_address is required.');
        }
        if(!isset($param['amount'])){
            return $this->error_res('amount is required.');
        }
        $this->param['to_address'] = $param['to_address'];
        $this->param['amount'] = $param['amount'];
        
        $res = $this->curl_call($url, $this->param);
        
        return $res;
    }
    /**
     * get transaction details of given transaction id.
     * @param string $id pass id to get transaction detail.
     * @return array() returns array with success or error response.
     */
    public function get_transaction($id){
        $url = $this->url.$this->coin.'/get-transaction';
        
        $this->param['id'] = $id;
        
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
        
        if(!isset($param['amount'])){
            return $this->error_res('amount is required.');
        }
        if(!isset($param['notify_url'])){
            return $this->error_res('notify_url is required.');
        }
        $this->param['amount'] = $param['amount'];
        $this->param['notify_url'] = $param['notify_url'];
        
        if(isset($param['name'])){
            $this->param['name'] = $param['name'];
        }
        if(isset($param['currency'])){
            $this->param['currency'] = $param['currency'];
        }
        if(isset($param['expire_hours'])){
            $this->param['expire_hours'] = $param['expire_hours'];
        }
        if(isset($param['description'])){
            $this->param['description'] = $param['description'];
        }
        
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
