# wp-dich
Dependency Injection Container for WordPress Hook

## Getting Started
`WP_DICH()` class needs a Dependency Injection Container to get started.
You can use any Dependency Injection library you like, but instead of passing it directly as the constructor param you should pass a class that implements a Interface `\ThanksToIT\WPDICH\DIC_Interface`.

This class should have at least 1 method `get()` which will be used to get the object you'll need on your hooks.
And probably you don't even need to create this method because most of Dependency Injection Libraries already have them.

Anyway, there is already one class `\ThanksToIT\WPDICH\League_Container_DIC` created as an example of how it could work using a Dependency Injection Library called [Container](https://github.com/thephpleague/container), from The PHP League.

### Initializing WP_DICH Class
Example of how you can initialize WP_DICH class using thephpleague/container as the Depenceny Injection Container library.
```php
$di_container = new \ThanksToIT\WPDICH\League_Container_DIC();
$wp_dich = new \ThanksToIT\WPDICH\WP_DICH( $di_container );
```

### Using WP_DICH Hooks
```php
$wp_dich->add_action( 'wp_footer', array( 'object', 'any_method' ) );
```
