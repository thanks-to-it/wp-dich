# WP_DICH
Dependency Injection Container for WordPress Hooks

## Introduction
Have you ever wanted to know the [best way to initialize a class in WordPress](https://wordpress.stackexchange.com/questions/70055/best-way-to-initiate-a-class-in-a-wp-plugin)?
Of course there is no bullet proof answer to that, but what if there was a way to initialize a class in a smart way? Only when necessary? Only when a WordPress hook is run for instance? This is what **WP_DICH** offers you.

Besides that, as **WP_DICH** works with a Dependency Injection Container, you'll benefit from all the pros it can offer

## Getting Started
`WP_DICH()` class needs a Dependency Injection Container to get started.
You can use any Dependency Injection library you like, but instead of passing it directly as the constructor param you should pass a class that implements a `\Thanks_To_IT\WP_DICH\DIC_Interface` Interface.

This class should have at least 1 method `get()` which will be used to get the object you'll need on your hooks.
And probably you don't even need to create this method because most of Dependency Injection Libraries already have them.

Anyway, there is already one class `\Thanks_To_IT\WP_DICH\League_Container_DIC` created as an example of how it could work using a Dependency Injection Library called [Container](https://github.com/thephpleague/container), from The PHP League.

### Initializing WP_DICH Class
Example of how you can initialize WP_DICH class using [thephpleague/container](https://github.com/thephpleague/container) as the Depenceny Injection Container library.
```php
$di_container = new \Thanks_To_IT\WP_DICH\League_Container_DIC();
$wp_dich = new \Thanks_To_IT\WP_DICH\WP_DICH( $di_container );
```

### Setup your Dependency Injection Container
Imagine you have a class `Your_Project\Object`.
The Dependency Injection Container has to know which class you want to lazy load. 
```php
$di_container->add('object', Your_Project\Object::class);
```

### Using WP_DICH Hooks
You can use the WordPress hooks functions you are used to like:
- add_action
- add_filter
- remove_action
- remove_filter

The following example will lazy load the class 'object' only inside the `wp_footer` hook.
```php
$wp_dich->add_action( 'wp_footer', array( 'object', 'any_method' ) );
```
