# Politicator.com

This started out a few years ago as a project to keep my programming chops up. The programming focus was on learning:

* the [Phalcon](https://phalconphp.com/) framework,
* to capture a [Twitter](https://twitter.com) stream,
* more machine learning techniques to do things with the data,
* more about OO code design,
* to use existing PHP and Javascript libraries where possible.

This is a work in progress but is fully functional. This code uses a more streamlined process to connect to Twitter
than [*fennb/phirehose*](https://github.com/fennb/phirehose) or [*
OwlyCode/StreamingBird*](https://github.com/OwlyCode/StreamingBird).

It requires PHP7, Phalcon, Composer, and the extension “Ds” available with PEAR. It is currently running on Debian 11.3
with the XFS file system,
Nginx 1.18, PHP 7.4, MongoDB 4.4, and Phalcon 4.1.
