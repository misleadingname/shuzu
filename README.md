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
 - Setup the webserver to be in the directory or a subdomain.

### Updating?
The easiest way to keep up with shuzu updates is to install `git` and pull to the repository every now and then.

## Configuration
### TEMPORARY!: I am working on a configuration file based solution! Where more things will be customiseable!
The only thing that is configure-able is the password in `/admintool.php`, simply open the file and edit the 4th file. By default it should look like this.
```injectablephp
// CHANGE ME!!!
$password = "CHANGE ME";
// CHANGE ME!!!
```