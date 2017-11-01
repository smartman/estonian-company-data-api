## Estonian company registry data API implemented in Laravel 5

## Installation instructions

First clone this repository and create database.

Run following commands:
 - composer update
 - configure database access details in .env file
 - replace QUUEUE_DRIVER with database. If you keep it default sync then script running will time out. 
 - php artisan migrate
 
This application needs to periodically update its data. For this you need to set up cron job an queue worker.
Initial data import can be done via 
<pre>php artisan tinker</pre> 
and running 
<pre>app(\Importer\DataImporter::class)->updateData();</pre>

Add following to your crontab to have data be updated periodically.
<pre>
* * * * * php /var/www/company_data_api/artisan schedule:run >> /dev/null 2>&1
</pre>

Run queue worker and preferably use supervisor to keep it running. 
<pre>
php artisan queue:work
</pre>

Supervisor configuration is described at [https://laravel.com/docs/5.5/queues#supervisor-configuration](https://laravel.com/docs/5.5/queues#supervisor-configuration)


## API documentation

Make the call to
<pre>[base_url]/api/company?name=[name]</pre>
Example call

[https://company-data.marguspala.com/api/company?name=smart+id](https://company-data.marguspala.com/api/company?name=smart+id) 


## License

This application is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
