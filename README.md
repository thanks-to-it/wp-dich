# wp-dich
Dependency Injection Container for WordPress Hook

## Getting Started

### Initializing WP_DICH Class
```php
$di_container = new \ThanksToIT\WPDICH\League_Container_DIC();
$wp_dich = new \ThanksToIT\WPDICH\WP_DICH( $di_container );
```

### Using WP_DICH Hooks
```php
$wp_dich->add_action( 'wp_footer', array( 'object', 'any_method' ) );
```
