# DRYVal

A lightweight library to make your data validation easy, with Laravel PHP Framework.

Make your models self validating, just define the validation rules in your Eloquent model. Whenever you try to create or update data with your model and if validation fails, an exception will be thrown.

## Installation

Add this to require section (in your composer.json):

    "usmanhalalit/dryval": "1.*@dev"

And run: `composer update`. Library on [Packagist](https://packagist.org/packages/usmanhalalit/dryval).

## Usage Example
```PHP
use Dryval\ValidationTrait;

class Campaign extends Eloquent {
    use ValidationTrait;

    protected static $rules = [
        'title' => 'required|min:5|max:50', // `title` is the filed in your table
        'description' => 'required|min:5|max:500',
    ];

}
```

Now every time a validation rule breaks a `Dryval\ValidationException` will be thrown.
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

This will set a flush message on session with key `error`. You're free to do whatever you want. Simple as that.

Now you don't have to validate input everywhere. Just save the data and it will be automatically validated.
DRYVal utilizes PHP 5.4 Traits to make this happen. So you are not forced to extend a class.


## More on Usage

### Custom Validation Messages
If you want custom validation messages then you can put a `static $messages` array in your model. Example:
```PHP
protected static $messages = [
     'unique' => 'This :attribute is already registered.'
];
```

### Base Model
If all of your Eloquent models extend a Base Model then you can `use` the trait in just your Base Model and you're good to go.

### Updating with Unique Rule
When you validate using `unique` rule and you want skip certain id then you can just use `:id:` placeholder.

Let me clarify, you don't want to allow duplicate email addresses in your users table. You add this rule in your model:
```PHP
protected static $rules = [
    'email' => 'required|email|unique:users,email'
];
```

Now a user signsup with me@example.com which is not already in database, that's fine.  But now what if the user is trying to update his profile with some changes but same email address? Yes, validation will fail.
To solve this problem Laravel accepts the 3rd param for unique rule. DRYVal makes it even easier just use `:id:` placeholder as 3rd param, its like a dynamic id. Example:

```PHP
protected static $rules = [
    'email' => 'required|email|unique:users,email,:id:'
];
```

For this feature, credit goes to [Role Model](https://github.com/betawax/role-model).

### ValidationException
DRYVal throws `Dryval\ValidationException` when validation fails.
You can catch this exception app wise as shown in the first example or you can catch this in your controller/class. The `getMessages()`(plural) method of this exception class will return the validation error messages (MessageBag) same as Laravel's `$validator->messages()`. [More here](http://laravel.com/docs/validation#working-with-error-messages).

You can also throw this exception anywhere you need.

___

&copy; 2014 [Muhammad Usman](http://usman.it/). Licensed under MIT license.
