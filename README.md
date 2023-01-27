CoinRemitter plugin for Laravel
===

Coinremitter is a [crypto payment processor](http://coinremitter.com). Accept Bitcoin, Tron, Binance (BEP20), BitcoinCash, Ethereum, Litecoin, Dogecoin, USDTERC20, USDTTRC20, Dash, Monero etc.

**What is the Crypto Payment Processor?**

The Crypto Payment Processor acts as a mediator between merchants and customers, allowing the merchant to receive payments in the form of cryptocurrency.

## Installation guide:
You can install Coinremitter’s plugin using the composer in Laravel:
```
composer require coinremitter/laravel
```
## Register service provider to your config/app.php like below : 

Add ```Coinremitter\CoinremiterServiceProvider::class``` line at the bottom in the 
```providers``` array
 ```
 'providers' => [
    Coinremitter\CoinremiterServiceProvider::class,
 ]
 ```
## Publish the configuration file to the config folder using the following command:
 ```
 php artisan vendor:publish --provider="Coinremitter\CoinremiterServiceProvider"
 ```

## Set credentials of all coins which you want to use from coinremitter in config/coinremitter.php like this:
If this file does not exist then create and set configuration like this. [How to get API key and Password ?](https://blog.coinremitter.com/how-to-get-api-key-and-password-of-coinremitter-wallet/)

```Note:``` Include specific coins in coinremitter.php that you wish to utilize in your system.

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
 
## Usage of the library: 
 
 You have to include the namespace of the package wherever you want to use this library like this:
 ```
 use Coinremitter\Coinremitter;
 ```
 after using name space you can access all the methods of library by creating object of class like,
 ```
 $btc_wallet = new Coinremitter('BTC');
 ```
 Here "BTC" must be in config/coinremitter.php file array.

### Get balance
You can get the balance of your wallet using the get_balance call.
```
$balance = $btc_wallet->get_balance();
```
This will return either a success response or an error response if something went wrong.The  success response is as shown below:
```
{
   "flag":1,
   "msg":"Get balance successfully",
   "action":"get-balance",
   "data":{
      "balance":0.2457,
      "wallet_name":"my-wallet",
      "coin_name":"Bitcoin"
   }
}
```

### Create a new wallet address
You can get a new wallet address using the following method:
```
$address = $btc_wallet->get_new_address();
```
Success response : 
```
{
   "flag":1,
   "msg":"New address created successfully .",
   "action":"get-new-address",
   "data":{
      "address":"MMtU5BzKcrewdTzru9QyT3YravQmzokh",
      "label":"",
      "qr_code":"https://coinremitter.com/qr/btc/image.png"
   }
}


```
Also, you can assign a label to your address with a passing parameter to the get_new_address method like this:
```
$param = [
    'label'=>'my-label'
];
$address = $btc_wallet->get_new_address($param);
```
The response will add the given label at the label key.
```
{
   "flag":1,
   "msg":"New address created successfully .",
   "action":"get-new-address",
   "data":{
      "address":"MMtU5BzKcrewdTzru9QyT3YravQmzokh",
      "label":"my-label",
      "qr_code":"https://coinremitter.com/qr/btc/image.png"
   }
}
```

### Validate wallet address
For validation of the wallet address, use the following method:
```
$param = [
    'address'=>'QdN2STEHi7omQwVMjb863SVP7cxm3Nkp'
];

$validate = $btc_wallet->validate_address($param);
```
response : 
```
{
   "flag":1,
   "msg":"Success !",
   "action":"validate-address",
   "data":{
      "valid":true
   }
}


```

### Withdraw amount
To withdraw the amount to a specific address the following method will be used:

```
$param = [
    'to_address'=>'MLjDMFsobgk9Etj8KUKSpmHM6qG2qFK',
    'amount'=>0.0001
];
$withdraw = $btc_wallet->withdraw($param);
```
Success response:
```
{
   "flag":1,
   "msg":"Amount Successfully Withdraw.",
   "action":"withdraw",
   "data":{
      "id":"5b5ff10a8ebb830edb4e2a22",
      "txid":"1147aca98ced7684907bd469e80f7482f40a1aaf75c1e55f7a60f725ba28",
      "explorer_url":"http://btc.com/exp/1147aca98ced7684907bd469e80f7482f40a1aaf75c1e55f7a60f725ba28",
      "amount":0.0001,
      "transaction_fees":"0.00002000",
      "processing_fees":"0.00460000",
      "total_amount":"0.00472",
      "to_address":"MLjDMFsobgk9Etj8KUKSpmHM6qG2qFK",
      "wallet_id":"5c42a0ab846fe75142cfb2",
      "wallet_name":"my-wallet",
      "coin_short_name":"BTC",
      "date":"2019-06-02 01:02:03"
   }
}
```
The dates received in the response are in the UTC format.

### Get transaction
Retrieve transaction information using the ID received from the "withdraw amount" response's ID or from the "id" field in the webhook using the following method.
```
$param = [
    'id'=>'5b5ff10a8ebb830edb4e2a22'
];
$transaction = $btc_wallet->get_transaction($param);
```
Success response:
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
if reponse data object contains ```type``` is equal to ```send``` then the response will be given as shown below:
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
        "date":"2018-08-15 15:10:42",
        "transaction_fees":0.001,
        "processing_fees":0.1,
        "total_amount":"2.10100000"
    }
}
```
The dates received in the response are in the UTC format.

### Get Transaction By The Address
Get the transaction using the address received using the following method:
```
$param = [
    'address' => 'MLjDMFsob8gk9EX6tj8KUKSpmHM6qG2qFK',
];
$invoice = $btc_wallet->get_transaction_by_address($param);
```
Success response : 
```
{
   "flag":1,
   "msg":"success",
   "action":"get-transaction-by-address",
   "data":[
      {
         "id":"5b7650458ebb8306365624a2",
         "txid":"7a6ca109c7c651f9b70a7d4dc8fa77de322e420119c5d2470bce7f08ba0cd1d6",
         "explorer_url":"http://coin-explorer-url/exp/7a6ca109c7c651f9b70a7d4dc8fa7...",
         "merchant_id":"5bc46fb28ebb8363d2657347",
         "type":"receive",
         "coin_short_name":"BTC",
         "wallet_id":"5c42ea0ab846fe751421cfb2",
         "wallet_name":"my-wallet",
         "address":"MLjDMFsob8gk9EX6tj8KUKSpmHM6qG2qFK",
         "amount":"2",
         "confirmations":3,
         "date":"2018-08-17 10:04:13"
      },
      {
         "id":"23sdew232158ebb8306365624a2",
         "txid":"7a6ca109c7c651f9b70fdgfg44er34re7de322e420119c5d2470bce7f08ba0cd1d6",
         "explorer_url":"http://coin-explorer-url/exp/2322ereer344c7c651f9b70a7d4dc8fa7...",
         "merchant_id":"3434df4w28ebb8363d2657347",
         "type":"receive",
         "coin_short_name":"BTC",
         "wallet_id":"5c42ea0ab846fe751421cfb2",
         "wallet_name":"my-wallet",
         "address":"MLjDMFsob8gk9EX6tj8KUKSpmHM6qG2qFK",
         "amount":"1",
         "confirmations":2,
         "date":"2018-08-17 10:05:13"
      }
   ]
}
```
The dates received in the response are in the UTC format.

### Create Invoice
You can create an invoice using the following method:
```
$param = [
    'amount'=>"15",      //required.
    'notify_url'=>'https://yourdomain.com/notify-url', //optional,url on which you wants to receive notification,
    'fail_url' => 'https://yourdomain.com/fail-url', //optional,url on which user will be redirect if user cancel invoice,
    'suceess_url' => 'https://yourdomain.com/success-url', //optional,url on which user will be redirect when invoice paid,
    'name'=>'random name',//optional,
    'currency'=>'usd',//optional,
    'expire_time'=>'20',//optional, invoice will expire in 20 minutes.
    'description'=>'',//optional.
];

$invoice  = $btc_wallet->create_invoice($param);
```

Success response:
```
{
   "flag":1,
   "msg":"success",
   "action":"create-invoice",
   "data":{
      "id":"5de7ab46b846fe6aa15931b2",
      "invoice_id":"BTC122",
      "merchant_id":"5bc46fb28ebb8363d2657347",
      "url":"https://coinremitter.com/invoice/5de7ab46b846fe6aa15931b2",
      "total_amount":{
         "BTC":"0.00020390",
         "USD":"2.21979838",
      },
      "paid_amount":[
      ],
      "usd_amount":"2.21979838",
      "conversion_rate":{
         "USD_BTC":"0.00009186",
         "BTC_USD":"10886.83"
      },
      "base_currency":"USD",
      "coin":"BTC",
      "name":"random name",
      "description":"",
      "wallet_name":"my-wallet",
      "address":"QbrhNkto3732i36NYmZUNwCo4gvTJK3992",
      "status":"Pending",
      "status_code":0,
      "notify_url":"http://yourdomain.com/notify-url",
      "suceess_url":"http://yourdomain.com/success-url",
      "fail_url":"http://yourdomain.com/fail-url",
      "expire_on":"2019-12-04 18:39:10",
      "invoice_date":"2019-12-04 18:19:10",
      "custom_data1":"",
      "custom_data2":"",
      "last_updated_date":"2019-12-04 18:19:10"
   }
}
```
The dates received in the response are in the UTC format.

### Get Invoice
Get invoice details using invoice_id received using the following method:
```
$param = [
    'invoice_id'=>'BTC02'
];
$invoice = $btc_wallet->get_invoice($param);

```
Success response:

```
{
    "flag":1,
    "msg":"success",
    "action":"get-invoice",
    "data":{
        "id":"5b7650458ebb8306365624a2",
        "invoice_id":"BTC02",
        "merchant_id":"5bc46fb28ebb8363d2657347",
        "url":"https://coinremitter.com/invoice/5b7650458ebb8306365624a2",
        "total_amount":{
             "BTC":"0.00020390",
             "USD":"2.21979838",
        },
        "paid_amount": {
            "BTC": "0.00020000",
            "USD": "2.167729279"
        },
        "usd_amount":"2.21979838",
        "conversion_rate":{
             "USD_BTC":"0.00009186",
             "BTC_USD":"10886.83"
        },
        "base_currency": "USD",
        "coin":"BTC",
        "name":"random name",
        "description":"",
        "wallet_name":"my-wallet",
        "address":"QbrhNkto3732i36NYmZUNwCo4gvTJK3992",
        "payment_history":[
                {
                    "txid":"c4b853d4be7586798870a4aa766e3bb781eddb24aaafd81da8f66263017b872d",
                    "explorer_url":"http://btc.com/exp/c4b853d4be7586798870a4aa766e3bb781eddb24aaafd81da8f66263017b872d",
                    "amount":"0.0001",
                    "date":"2019-12-04 18:21:05",
                    "confirmation":781
                },
                {
                    "txid":"a2541253ab72d7cf29f2f9becb1e31320dd0ed418f761ab1973dc9e412a51c7f",
                    "explorer_url":"http://btc.com/exp/a2541253ab72d7cf29f2f9becb1e31320dd0ed418f761ab1973dc9e412a51c7f",
                    "amount":"0.0001",
                    "date":"2019-12-04 18:22:23",
                    "confirmation":778
                }
        ],
        "status":"Under Paid",
        "status_code":2,
        "wallet_id": "6347e0e9f4efc676380afde7",
        "suceess_url":"http://yourdomain.com/success-url",
        "fail_url":"http://yourdomain.com/fail-url",
        "notify_url":"http://yourdomain.com/notify-url",
        "expire_on":"2019-12-04 18:39:10",
        "invoice_date":"2019-12-04 18:19:10",
        "custom_data1": "",
        "custom_data2": "",
        "last_updated_date":"2019-12-04 18:22:23"
    }
}
```
The dates received in the response are in the UTC format.

### Get Live Coin Price  in USD
Get the rate of the using the following method:
```
$rate = $btc_wallet->get_coin_rate();
```
Success response : 
```
{
   "flag":1,
   "msg":"success",
   "action":"get-coin-rate",
   "data":{
      "BTC":{
         "symbol":"BTC",
         "name":"Bitcoin",
         "price":10886.83
      },
      "LTC":{
         "symbol":"LTC",
         "name":"Litecoin",
         "price":47
      },
      "DOGE":{
         "symbol":"DOGE",
         "name":"DogeCoin",
         "price":235.26
      }
   }
}
```
### Get Crypto Rate
Get the crypto rate using fiat_symbol and fiat_amount received using the following method :
```
$param = [
    'fiat_symbol' => 'USD',
    'fiat_amount' => 1
];
$invoice = $btc_wallet->get_fiat_to_crypto_rate($param);
```
Success response : 
```
{
   "flag":1,
   "msg":"success",
   "action":"get-fiat-to-crypto-rate",
   "data":{
      "crypto_amount":"0.02123593",
      "crypto_symbol":"BTC",
      "crypto_currency":"Bitcoin",
      "fiat_amount":"1",
      "fiat_symbol":"USD"
   }
}
```


**For further reference please visit our [api documentation](https://coinremitter.com/docs)**
