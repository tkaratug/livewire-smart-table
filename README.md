# Livewire Smart Table
An advanced, dynamic datatable component with pagination, sorting, and searching including json data.

## Installation

You can install the package via composer:

```bash
composer require tkaratug/livewire-smart-table
```

## Requirements
This package uses livewire/livewire (https://laravel-livewire.com/) under the hood.

It also uses Bootstrap (https://getbootstrap.com/) for base styling.

Please make sure you include both of these dependencies before using this component.

## Usage
In order to use this component, you must create a new Livewire component that extends from LivewireSmartTable

You can use make:livewire to create a new component. For example.

```
php artisan make:livewire UserList
```

In the UserList class, instead of extending from the base Livewire Component class, extend from LivewireSmartTable class. Also, remove the render method. You'll have a class similar to this snippet.

```php
class UserList extends LivewireSmartTable
{
    //
}
```

To render the component in a view, just use the Livewire tag or include syntax

```blade
<livewire:user-list
   :query="$query" // required
   :columns="$columns" // required
   table-class="class for the table" // optional
/>
```

### Testing

```bash
composer test
```

### Changelog

- Initial release

### Security

If you discover any security related issues, please email tkaratug@hotmail.com.tr instead of using the issue tracker.

## Credits

- [Turan Karatuğ](https://github.com/tkaratug)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
