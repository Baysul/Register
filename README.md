# Register
Simple registration form for Kitsune.

## Prerequisites
* [npm and node](https://nodejs.org/) - Make sure you have Node 6.x, npm comes with it.
  * [Gulp](https://www.npmjs.com/package/gulp) - A task runner. In this project's case, it's used for compiling JS and CSS files, then minifying them. It also checks for syntax errors.

* [Composer](https://getcomposer.org/) - Dependency manager for PHP
  * Configured [to include](https://packagist.org/packages/google/recaptcha) the [ReCaptcha](http://www.google.com/recaptcha/) library.

## Installation
To install the prerequisites:

*Install node and npm from the above link*

*Install composer from the above link*

*Install gulp-cli as a global dependency*
```
npm install -g gulp-cli
```

Once you've installed all of the prerequisites, you will need to run the following commands in order for the form to actually have all of the files that it needs.

*Download npm packages*
```
npm install
```

*Download Composer dependencies*
```
composer update
```

*Run Gulp*
```
gulp default
```
or
```
gulp
```

## Configuration
You'll probably need to modify **register.php** (``$config``) in order for it to connect to your database. You'll also want to change the secret key ReCaptcha uses, so look for ``$recaptcha``.
