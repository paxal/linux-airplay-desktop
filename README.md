linux-airplay-desktop
=====================

Share your linux desktop wireless via Airplay.

This is just a php wrapper to launch the airplay client on the right server.

## Requirements

You should have :
  - a working java runtime environment (jre)
  - php 5.3+ installed
  - `avahi-browse` command-line binary

## Installation

Just download a binary in the release page.

## Usage

Just run `./airplay.phar`, and select the server your want to airplay to.

## Build the .phar

First, check your `phar.readonly` config option in your `php.ini` file (should be set to false).

Then, clone the project, [install composer](http://getcomposer.org/) into the root directory,
and launch `composer.phar install` command to install deps.

Eventually, launch the `./bin/build.php` script to build the `.phar` file.

## FAQ

*Q: Why is it so slow ?*

The desktop screen is sent as an image to the airplay server, this is not video.

*Q: Why use java ?*

Yes, a PHP version is available, I might work on that soon to lighten the phar, but we need an executable to create the screenshot right ?

## Credits

This package uses [open-airplay](https://code.google.com/p/open-airplay/) 
airplay.jar file to do the work.
