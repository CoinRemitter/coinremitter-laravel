# CoinRemitter Plugin For Laravel

Coinremitter is a [crypto payment processor](http://coinremitter.com). Accept Bitcoin, Bitcoin Cash, Litecoin, Dogecoin, Dash, Tron, Binance ,Tether USD ERC20,Tether USD TRC20 etc.View all supported currency [here](http://coinremitter.com/supported-currencies).

**What is the Crypto Payment Processor?**

The Crypto Payment Processor acts as a mediator between merchants and customers, allowing the merchant to receive payments in the form of cryptocurrency.

**If you want to use coinremitter API then refer this [api documentation](https://api.coinremitter.com/docs)**.

## Prerequisites
- A minimum of PHP 7.2 upto 8.4.
- Laravel Framework Installed.The plugin supports Laravel version 7.x upto 11.x
- Make sure [Composer](https://getcomposer.org/) is installed globally. It is required to manage dependencies.

## Installation Guide
**You can install Coinremitter’s plugin using the composer in Laravel:**
```bash
composer require coinremitter/laravel
```
**Register service provider for Laravel 10.x and earlier like below:**

- In `config/app.php`
Add ```Coinremitter\CoinremiterServiceProvider::class``` line at the bottom in the  ```providers``` array

 ```php
 'providers' => [
    Coinremitter\CoinremiterServiceProvider::class,
 ]
 ``` 

**Register service provider for Laravel 11.x like below:**
- In `bootstrap/providers.php` Add ```Coinremitter\CoinremiterServiceProvider::class``` line in array

**Publish the configuration file to the config folder using the following command:**
 ```bash
 php artisan vendor:publish --provider="Coinremitter\CoinremiterServiceProvider"
 ```

**Set credentials of all coins which you want to use from coinremitter in config/coinremitter.php like this:**
If this file does not exist then create and set configuration like this. [How to get API key and Password ?](https://blog.coinremitter.com/how-to-get-api-key-and-password-of-coinremitter-wallet/)

> **_NOTE:_** Include specific coins in coinremitter.php that you wish to utilize in your system.

 ```php
 <?php

 return [

        'coins' => [
            'BTC' => [
                'api_key' => 'API_KEY_FROM_WEBSITE',
                'password' => 'PASSWORD',
            ],
            'LTC' => [
                'api_key' => 'API_KEY_FROM_WEBSITE',
                'password' => 'PASSWORD',
            ],
            'ETH' => [
                'api_key' => 'API_KEY_FROM_WEBSITE',
                'password' => 'PASSWORD',
            ],
        ],
    ];
 ```

## Usage

 You have to include the namespace of the package wherever you want to use this library like this:
 ```
 use Coinremitter\Coinremitter;
 ```
 after using name space you can access all the methods of library by creating object of class like,
 ```
 $btc = new Coinremitter('BTC');
 ```

> **_NOTE:_** Here "BTC" must be in config/coinremitter.php file coins array.

Here’s an example using `BTC` as the currency. You can replace it with your desired currency's short name.
E.g. To create invoice of `Litecoin`. Use `LTC` short name.   
```
$ltc = new Coinremitter('LTC');
$invoice = $ltc->getInvoice('LTC');
```
To find the short names of supported currencies,you can [refer to this page](https://coinremitter.com/supported-currencies) or retrieve them using the [Supported Currencies API](https://api.coinremitter.com/docs#SupportedCurrencies).

### Get Wallet Balance
You can get the balance of your wallet using the getBalance call.
```php
$balance = $btc->getBalance();
```
This will return either a success response or an error response if something went wrong.The  success response is as shown below:
```php
{
    "success": true,
    "data": {
        "wallet_id": "6746c765xxxxxxxxxxxxxx",
        "wallet_name": "BTC-wallet",
        "coin_symbol": "BTC",
        "coin": "Bitcoin",
        "coin_logo": "https://api.coinremitter.com/assets/images/coins/32x32/BTC.png",
        "blockchain_network_name": "Bitcoin Main Net",
        "contract_address": "",
        "contract_address_url": "",
        "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/",
        "chain_id": "1",
        "remaining_withdraw_limit_24h": "49",
        "balance": "84.73000000",
        "minimum_deposit_amount": "0.1"
    }
}
```

### Create Wallet Address
You can get a new wallet address using the following method:
```php
$param = [
    "label"=>"BTC1" // optional,A label to assign to the new address.
]
$address = $btc->createAddress($param);
```
Success response : 
```php
{
    "success": true,
    "data": {
        "wallet_id": "6746c765xxxxxxxxxxxxxx",
        "wallet_name": "BTC-wallet",
        "coin": "Bitcoin",
        "coin_symbol": "BTC",
        "coin_logo": "https://api.coinremitter.com/assets/images/coins/32x32/BTC.png",
        "blockchain_network_name": "Bitcoin Main Net",
        "contract_address": "",
        "contract_address_url": "",
        "chain_id": "1",
        "address": "xxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "explorer_url": "https://www.blockchain.com/explorer/addresses/btc/xxxxxxxxxxxxxxxxxxxxxxxxxxxx?from=coinremitter",
        "label": "BTC1",
        "qr_code": "https://qr_code.com/qr?margin=1&size=200&text=xxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "minimum_deposit_amount": "1",
        "remaining_address_limit": 499,
        "wrn_msg": "",
        "expire_on": "2025-06-21 09:34:07",
        "expire_on_timestamp": 1750498447000
    }
}
```
### Validate Wallet Address
For validation of the wallet address, use the following method:
```php
$param = [
    'address'=>'MLjDMFsobgkxxxxxxxxxxxxxxxxxxxx' // required, The address to validate.
];

$validate = $btc->validateAddress($param);
```
Success response : 
```php
{
    "success": true,
    "data": {
        "valid": true
    }
}
```
### Estimate Withdrawal Cost
To calculate fees for various withdrawal speeds, use following method will be used:

```php
$param = [
    'address'=>'MLjDMFsobgkxxxxxxxxxxxxxxxxxxxx', // required, Total amount which you want to send.
    'amount'=>0.0001, // optional, Address of in which you want to send amount.
    'withdrawal_speed'=>'priority' // optional,The speed of withdrawal. Either 'priority', 'medium' or 'low'.Default speed take from your wallet settings.
];
$withdraw = $btc->withdraw($param);
```

Success response:
```php
{
    "success": true,
    "data": {
        "amount": "1.00000000",
        "transaction_fee": "0.10000000",
        "processing_fee": "0.01000000",
        "total_amount": "1.11000000",
        "fees_structure": {
            "transaction_fee": "0.01",
            "processing_fee": "0.23%"
        }
    }
}
```

### Withdraw Wallet Balance
To withdraw the amount to a specific address the following method will be used:

```php
$param = [
    'address'=>'MLjDMFsobgkxxxxxxxxxxxxxxxxxxxx', // required, Address of in which you want to send amount.
    'amount'=>0.0001, // required, Total amount which you want to send.
    'withdrawal_speed' => optional, The speed of withdrawal. Either 'priority', 'medium' or 'low'.Default speed take from your wallet settings.
];
$withdraw = $btc->withdraw($param);
```
Success response:
```php
{
    "success": true,
    "data": {
        "id": "674edd35765xxxxxxxxxxxxxx",
        "txid": "1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "amount": "1.00000000",
        "transaction_fees": "0.10000000",
        "processing_fees": "0.01000000",
        "total_amount": "1.11000000",
        "to_address": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "wallet_id": "6746c765xxxxxxxxxxxxxx",
        "wallet_name": "BTC-wallet",
        "coin_symbol": "BTC",
        "coin": "Bitcoin",
        "date": "2024-12-03 10:28:05",
        "transaction_timestamp": 1733221685000,
        "remaining_withdraw_limit_24h": "49"
    }
}
```
The dates received in the response are in the UTC format.

### Get Transaction
To retrieve transaction information using the ID received from the `Withdraw Wallet Balance` response's id or from the "id" field in the webhook using the following method.

```php
$param = [
    'id'=>'674edd35765xxxxxxxxxxxxxx' // required, Unique id of your transaction.
];
$transaction = $btc->getTransaction($param);
```
Success response:
```php
{
    "success": true,
    "data": {
        "id": "674edd35765xxxxxxxxxxxxxx",
        "txid": "1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "confirmations": 5,
        "required_confirmations": 3,
        "status": "confirm",
        "status_code": 1,
        "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx?from=coinremitter",
        "type": "receive",
        "coin": "Bitcoin",
        "coin_symbol": "BTC",
        "wallet_id": "6746c765xxxxxxxxxxxxxx",
        "wallet_name": "BTC-wallet",
        "address": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "label": "BTC1",
        "amount": "1.00000000",
        "date": "2024-12-02 09:18:55",
        "transaction_timestamp": 1733131135000
    }
}
```
if reponse data object contains ```type``` is equal to ```send``` then the response will be given as shown below:
```php
{
    "success": true,
    "data": {
        "id": "674edd35765xxxxxxxxxxxxxx",
        "txid": "1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "confirmations": 5,
        "required_confirmations": 3,
        "status": "confirm",
        "status_code": 1,
        "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx?from=coinremitter",
        "type": "send",
        "coin": "Bitcoin",
        "coin_symbol": "BTC",
        "wallet_id": "6746c765xxxxxxxxxxxxxx",
        "wallet_name": "BTC-wallet",
        "address": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "label": "BTC1",
        "amount": "1.00000000",
        "date": "2024-12-02 09:18:55",
        "transaction_timestamp": 1733131135000
    }
}
```
The dates received in the response are in the UTC format.

### Get Transaction By The Address
Get the transaction using the address received using the following method:
```php
$param = [
    'address' => 'MLjDMFsobgkxxxxxxxxxxxxxxxxxxxx', // required, Address of which you want to get transaction details.
];
$invoice = $btc->getTransactionByAddress($param);
```
Success response : 
```php
{
    "success": true,
    "data": {
        "coin": "Bitcoin",
        "coin_symbol": "BTC",
        "wallet_name": "BTC-wallet",
        "wallet_id": "6746c765xxxxxxxxxxxxxx",
        "address": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "expire_on": "2025-05-26 07:16:53",
        "expire_on_timestamp": 1748243813000,
        "label": "BTC1",
        "required_confirmations": 3,
        "confirm_amount": "2.00000000",
        "pending_amount": "0.00000000",
        "transactions": [
            {
                "id": "674edd35765xxxxxxxxxxxxxx",
                "txid": "1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
                "confirmations": 3,
                "status": "confirm",
                "status_code": 1,
                "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
                "type": "receive",
                "amount": "2.00000000",
                "date": "2024-11-29 11:15:20",
                "transaction_timestamp": 1732878920000,
                "confirm_amount": "2.00000000",
                "pending_amount": "0.00000000"
            }
        ]
    }
}
```
The dates received in the response are in the UTC format.

### Create Invoice
You can create an invoice using the following method:
```php
$param = [
    'amount'=>"10.6293",      //required,Invoice Amount.
    'name'=>'display name', //optional,It will display on invoice.
    'email'=>'USER_EMAIL', //optional,Send invoice mail on this email.
    'fiat_currency'=>'USD', //optional,Fiat currency code. E.g. USD, INR, EUR etc.
    'expiry_time_in_minutes'=>'20', //optional, Invoice expiry time in minutes. Default 1440 minutes.
    'notify_url'=>'https://yourdomain.com/notify-url', //optional,User will be redirected to this url once payment done.
    'success_url' => 'https://yourdomain.com/success-url', //optional,User will be redirected to this url when user cancel payment.,
    'fail_url' => 'https://yourdomain.com/fail-url', //optional,url on which user will be redirect if user cancel invoice,
    'description'=>'',//optional.The description for the invoice.
    'custom_data1'=>'',//optional.This data will be included in notify_url.
    'custom_data2'=>'',//optional.This data will be included in notify_url.
];

$invoice  = $btc->createInvoice($param);
```

Success response:
```php
{
    "success": true,
    "data": {
        "id": "674edd35765xxxxxxxxxxxxxx",
        "invoice_id": "0wBv07n",
        "url": "https://coinremitter.com/invoice/view/674edd35765xxxxxxxxxxxxxx",
        "total_amount": {
            "BTC": "0.03000000",
            "USD": "10.6293"
        },
        "paid_amount": {
            "BTC": "0.01000000",
            "USD": "3.5431"
        },
        "usd_amount": "10.63",
        "amount": "0.03000000",
        "conversion_rate": {
            "USD_BTC": "0.00282239",
            "BTC_USD": "354.31000000"
        },
        "fiat_currency": "",
        "coin": "Bitcoin",
        "coin_symbol": "BTC",
        "name": "BTC-wallet",
        "description": "",
        "wallet_name": "BTC-wallet",
        "wallet_id": "673d6a3fdfxxxxxxxxxxxxxx",
        "merchant_id": "6746c765xxxxxxxxxxxxxx",
        "status": "Pending",
        "status_code": 0,
        "success_url": "",
        "fail_url": "",
        "notify_url": "",
        "expire_on": "",
        "expire_on_timestamp": "",
        "invoice_date": "2024-12-03 10:41:13",
        "custom_data1": "",
        "custom_data2": "",
        "invoice_timestamp": 1733222473000,
        "delete_after": "2025-06-01 10:41:13",
        "delete_after_timestamp": 1748774473000
    }
}
```
The dates received in the response are in the UTC format.

### Get Invoice
Get invoice details using invoice_id received using the following method:
```php
$param = [
    'invoice_id'=>'FJkJEOx' // required, Unique id of invoice.
];
$invoice = $btc->getInvoice($param);

```
Success response:

```php
{
    "success": true,
    "data": {
        "id": "674edd35765xxxxxxxxxxxxxx",
        "invoice_id": "FJkJEOx",
        "url": "https://coinremitter.com/invoice/view/674edd35765xxxxxxxxxxxxxx",
        "total_amount": {
            "BTC": "0.03000000",
            "USD": "10.6293"
        },
        "paid_amount": {
            "BTC": "0.01000000",
            "USD": "3.5431"
        },
        "usd_amount": "10.63",
        "amount": "0.03000000",
        "conversion_rate": {
            "USD_BTC": "0.00282239",
            "BTC_USD": "354.31000000"
        },
        "fiat_currency": "",
        "coin": "Bitcoin",
        "coin_symbol": "BTC",
        "name": "BTC-wallet",
        "description": "",
        "wallet_name": "BTC-wallet",
        "wallet_id": "673d6a3fdfxxxxxxxxxxxxxx",
        "merchant_id": "6746c765xxxxxxxxxxxxxx",
        "payment_history": [
            {
                "txid": "1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
                "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/1796b1185xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx?from=coinremitter",
                "amount": "0.01000000",
                "date": "2024-11-20 05:34:41",
                "confirmation": 5,
                "required_confirmations": 3
            }
        ],
        "status": "Under Paid",
        "status_code": 2,
        "success_url": "",
        "fail_url": "",
        "notify_url": "",
        "expire_on": "",
        "expire_on_timestamp": "",
        "invoice_date": "2024-11-20 05:33:57",
        "custom_data1": "",
        "custom_data2": "",
        "invoice_timestamp": 1732080837000,
        "delete_after": "2025-05-19 05:33:57",
        "delete_after_timestamp": 1747632837000
    }
}
```
The dates received in the response are in the UTC format.

### Fiat to crypto rate
To get fiat to crypto rate.
```php
$param = [
    'fiat'=>'USD' // required, Fiat Symbol.
    'fiat_amount'=>'50' // required, Fiat Amount.
    'crypto'=>'BTC' // optional, Crypto Symbol.
];
$rate = $btc->fiatToCryptoRate();
```
Success response : 
```php
{
    "success": true,
    "data": [
        {
            "short_name": "ETH",
            "name": "Ethereum",
            "price": "0.01826164"
        },
        {
            "short_name": "BTC",
            "name": "Bitcoin",
            "price": "0.00078409"
        },
        {
            "short_name": "USDTERC20",
            "name": "Tether USD ERC20",
            "price": "50.00000000"
        }
    ]
}
```
### Crypto To Fiat Rate
To convert crypto rate into fiat rate.
```php
$param = [
    'crypto'=>'BTC' // optional, Crypto Symbol.
    'crypto_amount'=>'50' // required, Crypto Amount.
    'fiat'=>'USD' // required, Fiat Symbol.
];
$invoice = $btc->cryptoToFiatRate($param);
```
Success response : 
```json
{
    "success": true,
    "data": [
        {
            "code": "USD",
            "currency": "United States Dollar",
            "amount": "166.84"
        },
        {
            "code": "EUR",
            "currency": "Euro",
            "amount": "154.16"
        },
        {
            "code": "NZD",
            "currency": "New Zealand Dollar",
            "amount": "273.62"
        },
        {
            "code": "SGD",
            "currency": "Singapore Dollar",
            "amount": "225.23"
        }
    ]
}
```

### Get Supported currency
To get all supported currency and their detail.

```php
$supportedCurrency = $btc->getSupportedCurrency();
```
Success response : 
```json
{
    "success": true,
    "data": [
        {
            "coin": "Bitcoin",
            "coin_symbol": "BTC",
            "network_name": "Bitcoin Network",
            "explorer_url": "https://www.blockchain.com/explorer/transactions/btc/",
            "logo": "https://api.coinremitter.com/assets/images/coins/32x32/BTC.png",
            "minimum_deposit_amount": "0.00001",
            "price_in_usd": "63768",
            "fees": {
                "low": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.23",
                    "processing_fee": "0.23",
                    "transaction_fee_with_gasstation": "0.002",
                    "processing_fee_with_gasstation": "0.002"
                },
                "medium": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.003",
                    "processing_fee": "0.35",
                    "transaction_fee_with_gasstation": "0.002",
                    "processing_fee_with_gasstation": "0.002"
                },
                "priority": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.005",
                    "processing_fee": "0.5",
                    "transaction_fee_with_gasstation": "0.003",
                    "processing_fee_with_gasstation": "0.003"
                }
            }
        },
        {
            "coin": "Tether USD ERC20",
            "coin_symbol": "USDTERC20",
            "network_name": "USDT ERC20 Network",
            "explorer_url": "https://etherscan.io/tx/",
            "logo": "https://api.coinremitter.com/assets/images/coins/32x32/USDTERC20.png",
            "minimum_deposit_amount": "3.1",
            "price_in_usd": "1",
            "fees": {
                "low": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.23",
                    "processing_fee": "0.23",
                    "transaction_fee_with_gasstation": "0.0003",
                    "processing_fee_with_gasstation": "0.22"
                },
                "medium": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.002",
                    "processing_fee": "0.24",
                    "transaction_fee_with_gasstation": "0.0003",
                    "processing_fee_with_gasstation": "0.23"
                },
                "priority": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.003",
                    "processing_fee": "0.2",
                    "transaction_fee_with_gasstation": "0.0004",
                    "processing_fee_with_gasstation": "0.23"
                }
            }
        },
        {
            "coin": "Ethereum",
            "coin_symbol": "ETH",
            "network_name": "Ethereum Coin Network",
            "explorer_url": "https://etherscan.io/tx/",
            "logo": "https://api.coinremitter.com/assets/images/coins/32x32/ETH.png",
            "minimum_deposit_amount": "0.00012",
            "price_in_usd": "2737.98",
            "fees": {
                "low": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.00023",
                    "processing_fee": "0.00023",
                    "transaction_fee_with_gasstation": "0.0003",
                    "processing_fee_with_gasstation": "0.12"
                },
                "medium": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.0001",
                    "processing_fee": "0.0009",
                    "transaction_fee_with_gasstation": "0.0004",
                    "processing_fee_with_gasstation": "0.16"
                },
                "priority": {
                    "transaction_fees_type": "flat",
                    "processing_fees_type": "percentage",
                    "transaction_fee": "0.003",
                    "processing_fee": "0.3",
                    "transaction_fee_with_gasstation": "0.0005",
                    "processing_fee_with_gasstation": "0.2"
                }
            }
        }
    ]
}
```
