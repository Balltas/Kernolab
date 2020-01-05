## Technical Challenge for Kernolab

1. Set up an environment. 
    - PHP version - 7.1.9
    - Apache - 2.4.27
    - mySql - 5.7.19
    
2. clone this repo and run - **composer install**
3. Setup a database and change .env accordingly
4. Run migrations - **php artisan migrate**

From now on we can run API calls.

1. Try to insert transaction. The endpoint is POST: http://SOMEVHOST/api/create-transaction
2. Try to confirm the transaction. The endpoint is POST: http://SOMEVHOST/api/confirm-transaction
3. Run background tasks **php artisan schedule:run**
4. Try to get any transaction. The endpoit is GET: http://SOMEVHOST/api/get-transaction