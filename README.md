
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
* once a person subscribes for a plan that is recurring via any method or via a cart make sure that you loop through and .
* Subcription service would recieve the subscription and run a job to do the following

### JOURNAL APPROVAL TABLE
* Once a journal needs approval send the journal data to the approval table and debit the user's wallet for 1vault transactions and also do same for transactions going out.
*


## WELCOME ONBOARD

Hi Erin,
Welcome to the aktivate team a super team working on solving problems in the marketing tech space. My name is adeyemo olumide eronmonsele and i would be your direct report pending any tweak to the organizational structure. As a team we are open to changes and growth and we are constantly adapting and changing to make sure we work at the highest of levels and standards and maintain high levels of productivity. Working in a start up is an adventure and i happy to have you join our journey to building something remarkable. There are ups and downs but your attitude determines the outcome to every situation. There are few things that are important to us as a team :

* communication
* Mutual Respect
* Truth
* Integrity
* Passion

We want to build a different kind of company and so the values above are things we hold dear. Because we hold this values dearly we also operate an open door policy so that we can have difficult conversations anytime so that we can hold each other accountable. We have the goals we are looking to achieve and we take them seriously.

## HOW WE WORK
 We work majorly remotely and since we are remote we would be using tools that help us collaborate. Some of our tools are listed below i have broken them down into productivity and development :

#### PRODUCTIVITY
* Discord for team communication.
* Zoho Mail for emails.
* Trello for project management.
* Figma for user interface designs.

#### DEVELOPMENT
* Code-Commit
* Amazon web services(some select services)
* Postgresql
* Typescript and es6
* Adonisjs
* Nginx
* Tailwind css
* Python 
* Vue 3
* Nuxt 

### MEETINGS
In order for us to align and properly and harmonize towards a specific goal we would have our TECHNOLOGY team meetings HAPPEN on tuesday'S by 9:00am every week and any other day if the need be, being a remote team we assume that everybody is responsible hence we always ask that you over communicate and make sure you use our official team communications alot. Address impediments to your work on the channel and make sure to ask questions when you are not clear. One thing we live by is that no question is stupid and we need questions to help us align and clarify things. Our general meetings where we share our update with the general team happens every friday by 11:00am. We take meetings seriously because we dont want to leave gaps in our communication, so make sure you provide proper communication when there is reason you would be missing from a meeting. In case of emergencies please communicate with your direct line manager as soon as possible when you are available. Because your line manager also has to give a report about your absence. We also hold peer programming sessions with your direct line manager, this is solely dependent on your direct line manager.

### PUBLIC HOLIDAYS
We observe all public holidays at aktivate best believe we would not encroach on your holidays.

### YOUR RESPONSIBILITES

You would be primarily in charge of our efforts on the frontend for all our web platforms and other categories of products we might decide to create where your skills are applicable. We encourage people to explore at aktivate so i expect that you would try your hands at our backend development at some point in time. We embrace this philosophy because we want people to grow and try their hands on other things. We love explorers who try their hands on different things.
