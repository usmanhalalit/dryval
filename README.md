# DRYVal
A lightweight library to make your data validation easy.

## Example
```PHP
use Dryval\ValidationTrait;

class Campaign extends Eloquent {
    use ValidationTrait;

    protected static $rules = [
        'title' => 'required|min:5|max:50',
        'description' => 'required|min:5|max:500',
    ];

}
```

Now every time a validation rule is breaks a `Dryval\ValidationException` will be thrown.
To catch this put the code below in your `app/start/global.php` :

```PHP
App::error(function(\Dryval\ValidationException $e)
{
    try {
        return \Redirect::back()->with('error', $e->getMessages()->first())->withInput(\Input::except('password'));
    } catch (Exception $exception) {
        return \Redirect::to('/')->with('error', $e->getMessages()->first())->withInput(\Input::except('password'));
    }
});
```

This will set a flush message on session with key `error`. You're free to do whatever you want.


## Installation

Add this to require section (in your composer.json):

    "usmanhalalit/dryval": "1.*@dev"

And run:

    composer update

Library on [Packagist](https://packagist.org/packages/usmanhalalit/dryval).

&copy; 2014 [Muhammad Usman](http://usman.it/). Licensed under MIT license.
