# Shuzu
an opensource imageboard written in PHP.
___
# Set-up
To get shuzu running, you need to meet the *prerequisites*.

## Prerequisites
For shuzu to work, it needs to be in this environment:
- A PHP version that is greater than 8.0 (version's lower than 8.1 werent tested!).
- SQLite, image-magick extensions installed and enabled.
- An empty subdomain. Shuzu **can't** run in a subdirectory because of it's heavy reliance on routing.

## Installation
 - Pull the repo inside an empty directory. `git clone https://github.com/japannt/shuzu.git`
 - Follow the instructions in the section below.

### Updating?
The easiest way to keep up with shuzu updates is to use `git` and pull to the repository every now and then.

## Configuration
### Configuring shuzu:
### TEMPORARY!: I am working on a configuration file based solution! Where more things will be customiseable!
The only thing that is configure-able is the password in `/admintool.php`, simply open the file and edit the 4th file. By default it should look like this.
```php
// CHANGE ME!!!
$password = "CHANGE ME";
// CHANGE ME!!!
```
### Configuring the webserver: 
Enable the general webserver file-serving, and execute php as normal.  
**Important!** Use the router `/index.php` only when the server is about to yield a 404 error!  
### Example configurations:
```
Caddy:

```

## Good practice
It's generally a good idea to do these if you ask me.
 - Restrict the users from accessing anything in `/include`.
 - Use a strong password for the administration tool, and don't share it at all.
 - Use SSL for gods sake. (so many imageboards don't have SSL enabled.)

# TODO
 - [x] Working release.
 - [x] Fix media handling.
 - [ ] Proper configuration file
 - [ ] Captcha.
