# Register
Simple registration form for Kitsune.

## Prerequisites
* [NPM](https://nodejs.org/)
  * [Grunt](https://www.npmjs.com/package/grunt) - A task runner. In this project's case, it's used for compiling JS and CSS files, then minifying them. It also checks for syntax errors.
  * [Bower](http://bower.io/) - A package manager. We use it for Bootstrap and jQuery. In the scope of this project, it is a global module.

* [Composer](https://getcomposer.org/) - Dependency manager for PHP
  * Configured [to include](https://packagist.org/packages/google/recaptcha) the [ReCaptcha](http://www.google.com/recaptcha/) library.

## Installation
Once you've installed all of the prerequisites, you will need to run the following commands in order for the form to actually have all of the files that it needs.

*Download NPM packages*
```
npm update
```

*Download Bower dependencies*
```
bower update
```

*Download Composer dependencies*
```
composer update
```

*Run Grunt*
```
grunt default
```

## Configuration
You'll probably need to modify **register.php** (``$config``) in order for it to connect to your database. You'll also want to change the secret key ReCaptcha uses, so look for ``$recaptcha``.
