
## PROJECT NAME :  1VAULT API
1vault api services powers the 1vault mobile application for merchants and people looking to build their business on the 1vault platform.

- Subscribe to social media plans.
- Subscribe to website creation.

**Staging Status** : [![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F8d115b1d-522f-4917-b90c-8a5af5fea076%3Fdate%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/734550/sites/2162422)<br/>
**Production Status** :  [![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2Fdabc5087-bee3-4791-8a82-63c6f72dd49f%3Fdate%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/762121/sites/2259041)

#### SETUP
1vault application is built using the Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#### RESTRICTIONS
* someone cannot directly withdraw 1million at once
* Limit is gradually increased from 100k, 200k to 500k, 700k, 1million
* Set a threshold for transactions neeing approval to 1million 
* Once the transaction is 1million and above make sure that it goes into a cron job and the customer is debited and made to wait for the transaction to be cleared by the admin.
* Once it needs approval make sure the user is debited if it fails, make sure a reversal happens in form of a credit to the user's wallet.
* There should be a throttle on the transactions api to prevent multiple transactions happening at the same time to the same api.
* A middleware to prevent people from who don't have kyc from performing account creation with providus, Transactions, Airtime Purchase.
* Subscriptions happen from the wallet only.
#### SUBCRIPTION CREATION.
* once a person subscribes for a plan that is recurring via any method or via a cart make sure that you loop through and .
* Subcription service would recieve the subscription and run a job to do the following
#### JOURNAL APPROVAL TABLE
* Once a journal needs approval send the journal data to the approval table and debit the user's wallet for 1vault transactions and also do same for transactions going out.
#### STORE FRONT TABLES
* Limit the store front inventory to 50 items.
#### MIDDLEWARE 
* Make sure there is a middleware that checks if the user has been approved for transactions before outward transfers can happen. Which means approval for kyc for out
* Make sure there is a middleware that checks if a user has stayed on the platform for a number of days before he can begin to make transactions
* 1 million above transactions can not be made to go directly for both outward transfers.
#### JOBS
* Notify people of pending subscription charge.
* Auto renewal.
* Reversal of transactions.
* Approval of transactions.
* Verify transaction sent to an account
* Transaction notification mail and firebase.
* Airtime Purchase. 
* Electricity Purchase.
* Cable Tv Purchase.
* Once a transfer reach

#### 
* 
