
[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F7c4e2ab4-3b31-4579-9f11-fd67b867b67f%3Fdate%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/734550/sites/2161996)

### PROJECT NAME :  1vault Api

1vault api services powers the 1vault mobile application for merchants and people looking to build their business on the 1vault platform.

- Subscribe to social media plans.
- Subscribe to website creation.

### SETUP

1vault application is built using the Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

### RESTRICTIONS

* someone cannot directly withdraw 1million at once
* Limit is gradually increased from 100k, 200k to 500k, 700k, 1million
* Set a threshold for transactions neeing approval to 1million 
* Once the transaction is 1million and above make sure that it goes into a cron job and the customer is debited and made to wait for the transaction to be cleared by the admin.
* Once it needs approval make sure the user is debited if it fails, make sure a reversal happens in form of a credit to the user's wallet.
* There should be a throttle on the transactions api to prevent multiple transactions happening at the same time to the same api.
* A middleware to prevent people from who don't have kyc from performing account creation with providus, Transactions, Airtime Purchase.
* Subscriptions happen from the wallet only.

### SUBCRIPTION CREATION.
* once a person subscribes for a plan that is recurring via any method or via a cart make sure that you loop through and 
