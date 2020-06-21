# Livewire Smart Table
An advanced, dynamic datatable component with pagination, sorting, and searching including json data.

![Livewire Smart Table Demo](demo/livewire-smart-table.gif)

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

In the UserList class, instead of extending from the base Livewire Component class, extend from LivewireSmartTable class. Also, remove the render method. You'll have a class similar to this snippet. You must give columns to view in the table.

```php
class UserList extends LivewireSmartTable
{
    $columns = [
        'id' => [
            'name' => 'Id',
            'type' => 'string',
            'class' => 'text-danger',
        ],
        'name' => [
            'name' => 'Name',
            'type' => 'string',
        ],
        'email' => [
            'name' => 'E-Mail',
            'type' => 'string',
        ],
        'city' => [
            'name' => 'City',
            'type' => 'json',
            'from' => 'address',
            'value' => 'city',
        ],
        'actions' => [
            'name' => 'Actions',
            'type' => 'actions',
            'actions' => [
                [
                    'element' => '<button class="btn btn-sm btn-primary">View</button>',
                    'url' => 'http://example.com/users/{id}/details',
                ],
                [
                    'element' => '<button class="btn btn-sm btn-warning">Edit</button>',
                    'url' => 'http://example.com/users/{id}/edit',
                ],
            ],
        ],
    ];
}
```

To render the component in a view, just use the Livewire tag or include syntax

```blade
<livewire:user-list
   :query="$query" // required
   table-class="class for the table" // optional
/>
```

## Column Properties
### ```string```
Document will be added.
### ```link```
Document will be added.
### ```json```
Document will be added.
### ```actions```
Document will be added.

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email tkaratug@hotmail.com.tr instead of using the issue tracker.

## Credits

- [Turan Karatuğ](https://github.com/tkaratug)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
