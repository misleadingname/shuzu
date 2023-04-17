# Shuzu
an opensource imageboard written in PHP.
___
# Set-up
To get shuzu running, you need to meet the *prerequisites*.

## Prerequisites
For shuzu to work, it needs to be in this environment:
- A PHP version that is greater than 8.0 (version's lower than 8.1 weren't tested!).
- SQLite, image-magick extensions installed and enabled.
- An empty subdomain. Shuzu **can't** run in a subdirectory because of its heavy reliance on routing.

## Installation
 - Pull the repo inside an empty directory. `git clone https://github.com/japannt/shuzu.git`
 - Follow the instructions in the section below.

### Updating?
The easiest way to keep up with shuzu updates is to use `git` and pull to the repository sporadically.

## Configuration
### Configuring shuzu:

Copy `config.default.php` to `config.php` then edit the newly copied file.

### Configuring the webserver 
Enable the general webserver file-serving, and execute php as normal.  
**Important!** Use the router `/index.php` only when the server is about to yield a 404 error!  

### Example webserver configuration(s):

Caddy:
```caddy
shuzu.example.com {
	root public

	php_fastcgi unix//run/php-fpm/php-fpm.sock {
		index /index.php
		try_files {path} {path}/index.php /index.php
	}

	file_server {
		index off
	}

	try_files {path} {path}/index.php
}
```
Nginx:
```nginx
server {
    server_name shuzu.example.com;
    root /path/to/public;

    location / {
        try_files $uri $uri/ /index.php;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index /index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location = /index.php {
        fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index /index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location /index.php {
        try_files $uri $uri/ /index.php;
    }

    location = / {
        index off;
    }
}
```

*PR's for the readme on configurations for other webservers will be welcome!*

### Configuring the database
Shuzu **will not** work out of the box, it needs to be configured. Thankfully it's easy to do so.
 - After configuring the webserver, navigate to `/admintool` and authenticate with the login and password you set up earlier.
 - Look for the `!!! NUCLEAR OPTIONS !!!` section and click the `nuke` button.
 - After the database is nuked, you can start configuring everything else.
  
 *Do note, that the admintool isn't finished and is mostly in a dire state. pending rewrite.*

### Adding banners
To add new banners, put your banner to `/images/banners/{board}` *(Create if doesn't exist)*.  
Every banner should have a resolution of 468x80px.  

Shuzu each time will pick a random banner.

## Good practice
It's generally a good idea to do these if you ask me.
 - Restrict the users from accessing anything that isn't in `/public`.
 - Use a strong password for the administration tool, and don't share it at all.
 - Use SSL for god’s sake. (so many imageboards don't have SSL enabled.)

# TODO
 - [x] ~~Working~~ Functional release.
 - [x] Fix media handling.
 - [x] Proper configuration file.
 - [x] Sticky threads.
 - [x] User banning.
 - [x] `admintool` rewrite.
 - [x] Full release!!!