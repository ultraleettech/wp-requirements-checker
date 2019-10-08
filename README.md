# WordPress Requirements Checker
Helper library for WordPress plugins to check for minimum PHP and WP versions.

When there's any version mismatch when your plugin is activated, the library will display notices about required versions on the admin page. It will also automatically deactivate the plugin.

This way, when unsupported PHP language features or WP functionality is used in your plugin, it won't break the site and make it difficult for the admin to fix the issues.

## Usage
There are multiple ways to use this library, depending on the way you are developing your plugin. The main ones are using Composer, and just including the library manually. You can find instructions for both of these below.

### Using Composer
Require the library like so:

```
composer require ultraleet/wp-requirements-checker
```

In your main plugin file, first make sure that the Composer autoloader is included:

```php
require_once('vendor/autoload.php');
```

Then, instantiate the library and check the requirements. Make sure you continue loading your plugin only when the check passes:

```php
$requirementsChecker = new \Ultraleet\WP\RequirementsChecker(array(
    'title' => 'My WordPress Plugin Title',
    'php' => '7.2',
    'wp' => '4.9',
    'file' => __FILE__,
));

if ($requirementsChecker->passes()) {
    // Continue loading your plugin from separate files and/or classes.
}

// This should be the end of the main plugin file.
```

### Using manual loading
Download the file `src/RequirementsChecker.php` from this repository and place it somewhere within your project tree.

Let's say you placed it in the `lib` directory in your plugin root. Then include the file in your main plugin file:

```php
require_once('lib/RequirementsChecker.php');
```

The rest of the process is exactly the same as shown above.
