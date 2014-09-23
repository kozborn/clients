Client Report
========================


1) Installing
----------------------------------

Clone this repository

### Use Composer (*recommended*)

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `update` command:
    php composer.phar update

2) Generate clients using symfony2 console command
----------------------------------

    php app/console piotrk:generate-client --clients=clientsNo
    
3) set up your virtual hosts
----------------------------------
    
    <VirtualHost *:80>
        ServerName your-server-name
        ServerAlias domain.com.localhost
        ServerAdmin webmaster@localhost
    
        DocumentRoot /path/to/app/web
        <Directory /path/to/app/web>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride all
            Order allow,deny
            allow from all
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ /app.php [QSA,L]
            </IfModule>
        </Directory>
    </VirtualHost>
    
