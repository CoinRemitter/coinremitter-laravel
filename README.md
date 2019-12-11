# coinremitter-laravel
coinremitter for laravel

## installation guide.
you can install coin remitter plugin using composer in laraval : 
```
composer require coinremitter/laravel
```
## After installing pacakge 
 register service provider to your config/app.php like below : 
 ```
 'providers' => [
    Coinremitter\CoinremiterServiceProvider::class,
 ]
 ```
 ## After register provider,
 publish configuration file to config folder using following command:
 ```
 php artisan vendor:publish
 ```
 
 ## After publishing config file 
 set credentials of all coins which you want to use from coinremitter in config/coinremitter.php like : 
 ```
 return [
    'BTC'=>[
        'API_KEY'=>'YOUR_API_KEY_FROM_COINREMITTER_WALLET',
        'PASSWORD'=>'YOUR_PASSWORD_FOR_WALLET',
    ],
    'LTC'=>[
        'API_KEY'=>'YOUR_API_KEY_FROM_COINREMITTER_WALLET',
        'PASSWORD'=>'YOUR_PASSWORD_FOR_WALLET',
    ],
];
 ```
 
 ## Usage of library : 
 
 you have to include namespace of package wherever you want to use this library like,
 ```
 use Coinremitter\Coinremitter;
 ```
 after using name space you can access all the methods of library by creating object of class like ,
 ```
 $obj = new Coinremitter('BTC');
 ```
 here "BTC" must be in config/coinremitter.php file array.

### Get Balance : 
you can get balance of your wallet using get_balance call.
```
$balance = $obj->get_balance();
```
this will return either success response or error response if something went wrong.like below is the success response : 
```
{
    "flag":1,
    "msg":"Get balance successfully !",
    "action":"get-balance",
    "data":{
        "balance":123
        
    }
}
```

### Create New Wallet Address
You can get new wallet address using folowing method:
```
$address = $obj->get_new_address();
```
success response : 
```
{
    "flag":1,
    "msg":"New address created successfully !",
    "action":"get-new-address",
    "data":{
        "address":"MMtU5BzKcrew9BdTzru9QyT3YravQmzokh",
        "label":""
        
    }
}


```
also you can assign lable to your address with passing parameter to get_new_address method like:
```
$address = $obj->get_new_address("my_label");
```
the response will remian same as above response.

### Validate wallet address
for validation wallet address use folowing method:
```
$param = [
    'address'=>'your_Address_to_validate'
];

$validate = $obj->validate_address($param);
```
response : 
```
{
    "flag":1,
    "msg":"success",
    "action":"validate-address",
    "data":{
        "valid":true
        
    }
}


```
if ```data``` in response is ```1``` then the given address is valid,otherwise it's a invalid address.

### withdraw amount 
to withdraw amount to specific  address following method will use : 

```
$param = [
    'to_address'=>'YOUR_ADDRESS',
    'amount'=>123
];
$withdraw = $obj->withdraw($param);
```
success response : 
```
{
    "flag":1,
    "msg":"Amount Successfully Withdraw !",
    "action":"withdraw",
    "data":{
        "id":"5b5ff10a8ebb830edb4e2a22",
        "txid":"1147aca98ced7684907bd469e80cdf7482fe740a1aaf75c1e55f7a60f725ba28",
        "explorer_url":"http://btc.com/exp/1147aca98ced7684907bd469e80cdf7482fe740a1aaf75c1e55f7a60f725ba28",
        "amount":0.1,
        "transaction_fees":0.001,
        "processing_fees":0.00023,
        "total_amount":0.10123,
        "to_address":"YOUR_ADDRESS",
        "wallet_id":"5c42ea0ab846fe751421cfb2",
        "wallet_name":"wallet_name",
        "coin_short_name":"BTC",
        "date":"2019-06-02 01:02:03"
    }
}
```

### Get Transaction
get transaction detail using id received from ```withdraw amount``` response's ```id``` or from webhook's ```id``` field using following method :
```
$param = [
    'id'=>'5b5ff10a8ebb830edb4e2a22'
];
$transaction = $obj->get_transaction($param);
```
success response : 
```
{
    "flag":1,
    "msg":"success",
    "action":"get-transaction",
    "data":{
        "id":"5b5ff10a8ebb830edb4e2a22",
        "txid":"1147aca98ced7684907bd469e80cdf7482fe740a1aaf75c1e55f7a60f725ba28",
        "explorer_url":"http://btc.com/exp/1147aca98ced7684907bd469e80cdf7482fe740a1aaf75c1e55f7a60f725ba28",
        "type":"receive",
        "merchant_id":"5bc46fb28ebb8363d2657347",
        "coin_short_name":"BTC",
        "wallet_id":"5c42ea0ab846fe751421cfb2",
        "wallet_name":"wallet_name",
        "address":"QYTZkkKz7n1sMuphtxSPdau6BQthZfpnZC",
        "amount":0.0003,
        "confirmations":3,
        "date":"2018-08-15 15:10:42"
    }
}
```
if reponse data object contains ```type``` is equal to ```send``` then response will be given as below
```
{
    "flag":1,
    "msg":"success",
    "action":"get-transaction",
    "data":{
        "id":"5b5ff10a8ebb830edb4e2a22",
        "txid":"1147aca98ced7684907bd469e80cdf7482fe740a1aaf75c1e55f7a60f725ba28",
        "explorer_url":"http://btc.com/exp/1147aca98ced7684907bd469e80cdf7482fe740a1aaf75c1e55f7a60f725ba28",
        "type":"send",
        "merchant_id":"5bc46fb28ebb8363d2657347",
        "coin_short_name":"BTC",
        "wallet_id":"5c42ea0ab846fe751421cfb2",
        "wallet_name":"wallet_name",
        "address":"QYTZkkKz7n1sMuphtxSPdau6BQthZfpnZC",
        "amount":0.0003,
        "confirmations":3,
        "date":"2018-08-15 15:10:42"
        "transaction_fees":0.001,
        "processing_fees":0.1,
        "total_amount":"2.10100000"
        
    }
}
```
### Create Invoice
you can create invoice using following method : 
```
$param = [
    'amount'=>123,      //required.
    'notify_url'=>'https://notification.url', //optional,url on which you wants to receive notification,
    'name'=>'',//optional,
    'currency'=>'usd',//optional,
    'expire_hours'=>'',//optional,
    'description'=>'',//optional.
];

$invoice  = $obj->create_invoice($param);
```

success response : 
```
{
    "flag":1,
    "msg":"Invoice successfully created !!!",
    "action":"create-invoice",
    "data":{
        "id":"5b7650458ebb8306365624a2",
        "invoice_id":"BTC02",
        "merchant_id":"5bc46fb28ebb8363d2657347",
        "url":"https://coinremitter.com/invoice/5b7650458ebb8306365624a2",
        "total_amount":0.1,
        "paid_amount":0,
        "usd_amount":800,
        "coin":"BTC",
        "name":"random name",
        "description":"",
        "wallet_name":"wallet_name",
        "address":"rger54654fgsd4h6u7dgsg",
        "status":"Pending",
        "status_code":0,
        "notify_url":"http://yourdomain.com/notify-url",
        "suceess_url":"http://yourdomain.com/success-url",
        "fail_url":"http://yourdomain.com/fail-url",
        "expire_on":"2018-12-06 10:35:57",
        "invoice_date":"2019-12-04 18:19:10",
        "last_updated_date":"2019-12-04 18:19:10"
    }
}

```

### Get Invoice
get invoice detail using invoice_id received using following method :
```
$param = [
    'invoice_id'=>'ETH002'
];
$invoice = $obj->get_invoice($param);
```
success response : 
```
{
   "flag":1,
   "msg":"success",
   "action":"get-invoice",
   "data":{
      "id":"5b7650458ebb8306365624a2",
      "invoice_id":"ETH002",
      "merchant_id":"5bc46fb28ebb8363d2657347",
      "url":"http://192.168.0.112/coinremitter/public/invoice/5b7650458ebb8306365624a2",
      "total_amount":0.0009,
      "paid_amount":0,
      "usd_amount":800,
      "coin":"ETH",
      "name":"random name",
      "description":"Hello world",
      "wallet_name":"New Test-LTC",
      "address":"rger54654fgsd4h6u7dgsg",
      "payment_history":[
         {
            "txid":"c4b853d4be7586798870a4aa766e3bb781eddb24aaafd81da8f66263017b872d",
            "explorer_url":"http://btc.com/exp/c4b853d4be7586798870a4aa766e3bb781eddb24aaafd81da8f66263017b872d",
            "amount":0.005,
            "date":"2019-12-02 12:09:02",
            "confirmation":781
         },
         {
            "txid":"a2541253ab72d7cf29f2f9becb1e31320dd0ed418f761ab1973dc9e412a51c7f",
            "explorer_url":"http://btc.com/exp/a2541253ab72d7cf29f2f9becb1e31320dd0ed418f761ab1973dc9e412a51c7f",
            "amount":0.005,
            "date":"2019-12-02 12:15:02",
            "confirmation":778
         }
      ],
      "status":"Pending",
      "status_code":0,
      "suceess_url":"http://yourdomain.com/success-url",
      "fail_url":"http://yourdomain.com/fail-url",
      "expire_on":"2018-12-06 10:35:57",
      "invoice_date":"2018-08-17 10:04:13",
      "last_updated_date":"2018-08-17 10:04:13"
   }
}
```

### Get Coin Rate
get coin rate using following method :
```
$rate = $obj->get_coin_rate();
```
success response : 
```
{
   "flag":1,
   "msg":"success",
   "action":"get-coin-rate",
   "data":{
      "BTC":{
         "symbol":"BTC",
         "name":"Bitcoin",
         "price":7289.01
      },
      "LTC":{
         "symbol":"LTC",
         "name":"Litecoin",
         "price":145.51
      },
      "DOGE":{
         "symbol":"DOGE",
         "name":"DogeCoin",
         "price":0.0001
      }
   }
}
```

**for further reference please visit our [api documentation](https://coinremitter.com/docs)**
