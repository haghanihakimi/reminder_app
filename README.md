# Todo (Reminder) Laravel Web App
### Author: Haghani Hakimi

#### Overview:

Todo OR Reminder app is a simple project I created with Laravel PHP Framework.<br>
On this web app, users can have their own account and they can create **reminders** as many as they want.
##### Features of this app:
1. Create & manage account
    * Users can temporary & permanently delete their account.
2. Create & manage reminders
    * Users can create reminders
    * Users can edit & delete reminders at any time.
    * Users can ignore any reminder at anytime
        * By ignoring a reminder, user will not receive any notification
        * Ignored reminder which is shared either publicly or privately, will be invisible to other users.
    * Overdue reminders will permanently deleted from database.
    * Users can share any reminder publicly or privately.
        * Private shared reminders have a **unique** ```URL``` and users with that unique URL can see shared reminder.
        * Every user with a normal URL can see **PUBLICLY** shared reminders!
    * Users can choose whether they receive an email notification or not!

And there are some more features in this simple application.<br/>

##### Technologies used in this app:
1. HTML & CSS
2. PHP Laravel & Vue JS 2
3. MySQL
4. Laravel Pusher

#### Default user account password:
The default password for user account is: **password**

#### Basic requirements to run and test application:
1. NPM/NodeJS ([Download it here](https://nodejs.org/en/))
2. PHP Composer ([Download it here](https://getcomposer.org/download/))
3. **[Pusher](https://pusher.com/)** account to have required keys (**100% FREE**)
4. **[MailTrap](https://mailtrap.io/signin)** account to have reuqired keys (**100% FREE**)
5. A local server on your system, such as **[WAMPServer](https://www.wampserver.com/en/)** or **[XAMPP](https://www.apachefriends.org/download.html)**


#### Note: Laravel DebugBar is installed. Keep APP_DEBUG to TRUE to be able to use this tool during Development Stage.


## Configure & run the project!
1. Clone repository: <br>
    ``` $ git clone https://github.com/haghanihakimi/reminder_app ```
2. Install **node modules** <br>
    ```$ npm install```
3. Install Laravel plugins and required packages: <br>
    ```$ composer install```
4. Build assets: <br>
    ```npm run dev```
5. Setup configuration: <br>
    ```cp .env.example .env```
6. Generate application key: <br>
    ```php artisan key:generate```
7. Create a new MySQL database and name it **reminders**
8. Run database migration and fill **users** & **reminders** tables with random data: <br>
    ```php artisan migrate:fresh --seed```
9. Run the dev server (the output will give the address): <br>
    ```php artisan serve``` <br>
    OR <br>
    ```php artisan serve --port=443``` <br>
