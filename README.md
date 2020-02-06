# WP_DICH
**WP_DICH** offers a way to work with WordPress hooks smartly using a Dependency Injection Container, allowing lazy loading, making classes to be loaded only when required.

## The Challenge
Let's suppose you simply want to call a method `method_a()` from a class `Any_Class` only when a specific WordPress hook is run. How would you do it? There are at least 3 ways that come to my mind: 

1. ### :-1: Object Method Call
The problem here is you are initializing `Any_Class()` before it's necessary, as you only need the method on `wp_footer` hook.  
```php
class Any_Class{
	public function method_a(){}
}
$any_class = new Any_Class();
add_action( 'wp_footer', array( $any_class, 'method_a') );
```

2. ### :-1: Using Anonymous Functions 
The disadvantage here is you're not able to remove the action with `remove_action()` 
```php
class Any_Class{
	public function method_a(){}
}
add_action( 'wp_footer', function(){
	$any_class = new Any_Class();
	$any_class->method_a();
});
```

3. ### :-1: Using Static Methods
With the Static Methods approach, at least your class is loaded at the proper time, but quite often, from a design standpoint, it's better to stick to non-static methods. You can't override them, they are more difficult to test and you end up having to design other things around it as static too. 
```php
class Any_Class{
	public static function method_a(){}
}
add_action( 'wp_footer', array('Any_Class', 'method_a') );
```


---


### :ok_hand: The WP_DICH Solution
**WP_DICH** combines the advantage of the Object Method Call, using a non static method, with the benefits of loading the class only when it's required. Check how it's simple:

First you need to pass a Dependency Injection Container Interface to `WP_DICH()`.  
```php
$dic  = new \Thanks_To_IT\WP_DICH\DIC();
$dich = new \Thanks_To_IT\WP_DICH\WP_DICH( $dic );
```
> WP_DICH already offers a small Dependency Injection Container, thanks to [Carl Alexander](https://carlalexander.ca/dependency-injection-wordpress/), but you can use any library you want, like [thephpleague/container](https://github.com/thephpleague/container) or [php-di](http://php-di.org/) for example. You just have to implement a `Psr\Container\ContainerInterface` with 2 methods, `get()` and `has()`

Then you just have to setup your container as you like:
```php
$dic['any_class_alias'] = function () {
	return new Any_Class();
};
```

Now if you create your hook using the class alias you've configured on the container as the first parameter of the array, instead of calling a static method, it will load your class only when `wp_footer` hook is run and then `method_a` will be called.
```php
$dich->add_action( 'wp_footer', array( 'any_class_alias', 'method_a') );
```

## WP_DICH hooks
You can use the WordPress hook functions you are used to, like:

- `$dich->add_action`
- `$dich->add_filter`
- `$dich->remove_action`
- `$dich->remove_filter`

## Services
As we are using a Dependency Injection Container Library we may benefit from some interesting features, like services, allowing to instantiate your classes only once. Yes, no need to think about Singletons anymore.

```php
$dic['any_class_alias'] = $dic->service( function () {
	return new Any_Class();
} );
```
Now everytime you call for **'any_class_alias'** the same object will be returned instead of creating a new one every time.


## Installation
**Composer**
Add `thanks-to-it/wp-dich` to the require-dev or require section of your project's `composer.json` configuration file, and run 'composer install':
```json
{
    "require": {
        "thanks-to-it/wp-dich":"dev-master"
    }
}
```
