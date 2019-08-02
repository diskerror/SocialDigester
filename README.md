# Politicator.com
This started out a few years ago as a project to keep my programming chops up. The programming focus was on learning:

* the [Phalcon](https://phalconphp.com/) framework,
* to capture a [Twitter](https://twitter.com) stream,
* more machine learning techniques to do things with the data,
* more about OO code design,
* to use existing PHP and Javascript libraries where possible.

This is a work in progress but is fully functional. The code that is currently live on [Politicator.com](http://politicator.com) is tagged with "live_{date_time}".

This code uses a more streamlined process to connect to Twitter than [*fennb/phirehose*](https://github.com/fennb/phirehose) or [*OwlyCode/StreamingBird*](https://github.com/OwlyCode/StreamingBird).

It requires PHP7, Phalcon, Composer, and the extension “Ds” available with PEAR. It is currently running on a ten year old box with Debian 9 (stretch), Nginx 1.10, PHP 7.2, the command line tool “curl”, and Phalcon 3.4 with a consumer fiber internet connection.
