# KeysShop Project

The KeysShop project is a web-based platform designed to manage and sell digital keys for various software, games, and licenses.

The KeysShop was developed almost entirely from scratch using PHP, without relying on any frameworks.

> The KeysShop project was developed as part of a coursework assignment for the [Subotica Tech - College of Applied Sciences](https://www.vts.su.ac.rs/) program, and it is not intended for commercial use. 
> 
> However, despite being an educational project, it is fully functional and operates just like a real-world e-commerce platform.

* [Libraries](#libraries)
* Installing
    * Configuration
    * Server
* [Project structure](#project-structure)
* [MVC (Model-View-Controller)](#mvc-model-view-controller)
    * [BaseModel](#basemodel)
    * [BaseController and Page Rendering](#basecontroller-and-page-rendering)
    * [Router](#router-and-url-handling)
* [Ajax](#ajax)

## Libraries

The website is built entirely from scratch using pure PHP, without relying on any frameworks. This was done as an experimental project to enhance personal skills. Despite this, some libraries were used to add specific functionality:

- [\[PHP\] PHPMailer](https://github.com/PHPMailer/PHPMailer): A library for sending emails, used for password recovery functionality.
- [\[JS\] Swiper.js](https://github.com/nolimits4web/swiper): A highly customizable library for creating sliders and carousels.
- [\[JS\] Toastify.js](https://github.com/apvarun/toastify-js): A lightweight library for creating beautiful and customizable toast notifications.

## Project Structure

The project is organized as follows:

- `apps/` - Contains all mini-applications.
  - `{app_name}/` - A single mini-application.
    - `templates/` - Page views.
      - `components/` - Views for individual components.
    - `components.php` - Functions for handling the logic and rendering of specific components.
    - `functions.php` *(optional)* - Utility functions specific to the application.
    - `models.php` - Models for creating and managing objects.
    - `urls.php` - Defines an array of routes that map to controllers for rendering pages. These routes are later linked to the root `urls.php`.
- `assets/` - Static files such as JavaScript, CSS, and images.
- `includes/` - Core PHP scripts and libraries.
- `media/` - Uploaded files stored on the server.
- `.htaccess` - Configuration file for the web server (e.g., URL rewriting).
- `config-default.php` - A template configuration file. Can be used as a base to create `config.php`.
- `config.php` - The main configuration file, containing constants and key settings.
- `db.php` - Functions for interacting with the database.
- `functions.php`** - Global utility functions used across the project.
- `index.php` - The entry point for the site. Loads the router, configuration files, and more.
- `urls.php` - Combines all `urls.php` files from mini-applications into a unified router.


## MVC (Model-View-Controller)

To interact with database objects, we use classes inherited from `BaseModel`. For rendering pages, all logic is implemented in controllers inherited from `BaseController`, with a specified path to the corresponding view.

All views are connected via the router.

### BaseModel

The `BaseModel` class is responsible for managing data stored and processed on the server. All other models inherit from this base class.

#### To create a model, the following fields need to be defined:

- `public $field_{column_name}`: Public fields corresponding to the current table's columns, which will later be accessible.
- `static protected $table_name`: The name of the database table.
- `static protected $table_fields`: Specifies the fields and their data types (`int`, `string`, `bool`, or `DateTime`):
```php
static protected $table_fields = [
    'id'            => 'int',
    'user_id'       => 'int',
    'recovery_slug' => 'string',
    'created_at'    => 'DateTime',
    'is_used'       => 'bool'
];
```
- `static protected $additional_fields`: Additional fields to be joined with the current model. The field names will later be the same as the variables. (Field names from the main table are prefixed with `obj.{column_name}`):
```php
static protected $additional_fields = [
    [
        "field"      => [
            "tb2.name AS platform_title",
            "tb2.icon_html AS platform_icon",
            "tb2.background_color AS platform_background"
        ],
        "join_table" => "taxonomies tb2 ON tb2.id = obj.platform_id"
    ]
];
```
- `static protected $search_fields`: A list of fields to use for search queries. (Field names from the main table are prefixed with `obj.{column_name}`):
```php
static protected $search_fields = ['obj.title'];
```
#### Methods in BaseModel:
* `static init_table()`: Creates a new table for the model (used only in `setup.php`). Can use `db_query()` internally.
* `static filter(fields, sort_by, count, field_relation, offset, search, additional_fields)`: Retrieves an array of objects based on the specified filter.
```php
$objects = CustomModel::filter(
    [
        // Field filters
        [
            'name'       => 'obj.sales',   // Field name
            'type'       => '>',          // Comparison type (=, >, <, >=, <=, LIKE, IN). Default is "="
            'value'      => 20,           // Value to compare
            'is_having'  => false         // Search in WHERE or HAVING (not tied to the model)
        ]
    ],
    ['-obj.sales', 'obj.title'], // Sorting (prefix with "-" for descending order)
    10,                          // Number of objects to retrieve (COUNT)
    'AND',                       // Combine WHERE/HAVING with AND or OR
    0,                           // Offset
    'detroit',                   // Search value
    []                           // Additional fields (same as `$additional_fields`)
);
```
- `static get(fields, sort_by)`: Similar to `filter()`, but retrieves only the first matching model. Returns false if none is found.
- `static count(fields, search)`: Gets the count of objects matching the filter.
- `is_saved()`: Checks if the current object is saved in the database.
- `delete()`: Deletes the object from the database.
- `save()`: Saves the object to the database (either updates or creates a new record).
---
- `after_save()`: Additional functionality to execute after saving (optional).
- `valid()`: Validates the data. If there are issues, returns an array of string messages.

### BaseController and Page Rendering

For managing a specific page, we use a class that extends the `BaseController`, located at `/includes/base_controller.php`.

Within this class, we can implement and modify the following methods and fields:

* `$template_name` - The path to the view. Typically located in the `templates/` folder of the current application.
* `$allow_role` - Specifies which user role is permitted to access the view (`null`, `"user"`, or `"admin"`).
* `get_model()` - Retrieves a specific model associated with the page (e.g., `SingleProductController`).
* `get_context_data()` - Passes an array of variables in `$context` to the view.
* `distinct()` - Provides additional functionality before rendering the view.
* `post()` - Executes only if the page is accessed via the POST method.

### Router and URL Handling

The main `Router` class is located at `includes/router.php`. This class serves as the core for routing and displaying the appropriate controller based on the path.

#### Classes Overview

- **`Router`**: The primary class used for determining which controller to render based on the requested path.
- **`Path`**: A helper class where the path to a controller and the link name are specified.
- **Exception Classes**: Used for handling errors, including:
  - `PageError` (general, 500)
  - `PermissionDeniedHttp403`
  - `UnauthorizedHttp401`
  - `NotFoundHttp404`

#### Defining Routes

In mini-applications, routes are defined in a `urls.php` file, where paths and controllers are specified. Variables like `slug` or `id` can be passed using placeholders such as `[:string]` or `[:int]`.

```php
require_once APPS_PATH . '/my_app/controllers.php';

$my_app_urls = [
    // path, controller, name
    new Path('/item/[:string]', new SingleController(), 'single'),
    new Path('/home', new HomeController(), 'home'),
];
```

#### Integrating Routes into the Main Application**

The array of mini-application routes is then added to the **root directory**'s `urls.php` file, specifying the group name. Additionally, error controllers (e.g., "default", 404, 500) can also be defined:

```php
require APPS_PATH . '/my_app/urls.php';

Router::includes($my_app_urls, "myapp");
Router::set_error_controller('default', new ErrorController());
```


## Ajax
The website supports AJAX functionality through a dedicated mini-application called `ajax` and an `AjaxController` (accessible via `/ajax`). This setup enables features such as:
* Adding items to the wishlist
* Real-time search
* Adding items to the cart
* Viewing the cart

### How It Works

1. **Data Processing:** In the `AjaxController`, data is sent via the request body in JSON format. The data is then processed by a function specified in the action attribute of the JSON object.
2. **Creating a Custom Action:** To create a custom action, you need to add a new function in `apps/ajax/functions.php`.
    * The function must be named `ajax_{your_action_name}`.
    * It takes an associative array from the args attribute of the JSON object as its argument.
    * The function should return an array (either associative or indexed), which will be encoded into JSON for the client.
    * Error Handling: To trigger an error, return `get_ajax_error($message, $error_code)`
3. **Frontend Interaction:** On the frontend, you can use the `fetch()` API or an existing `sendAjax` function. The sendAjax function accepts the following parameters:
    * `action` — The name of the action to be executed.
    * `args` — An object containing arguments to be passed to the action.
    * `onLoad` — A callback function executed when the request starts.
    * `onSuccess` — A callback function executed upon a successful response.
    * `onError` — A callback function executed if an error occurs.


### Example: Custom Action in PHP (backend) and Javascript (frontend)

**PHP (backend)**
```php
// Example action: Return a number with a message
function ajax_my_number($args) {
    $result = array();

    // Validate the input
    $num = $args['number'];
    if (!isset($num) || !is_numeric($num)) {
        return get_ajax_error("Invalid number", 400); // Return error for invalid input
    }

    // Prepare the response
    $result['message'] = "My number is " . $num;

    return json_encode($result, JSON_PRETTY_PRINT); // Return JSON-encoded result
}
```
**Javascript (frontend)**
```js
sendAjax(
    // action
    "my_number", 
    // args
    {
    number: 5
    },
    // onLoad
    () => {
        console.log('loading...');
    },
    // onSuccess
    (result) => {
        console.log(result.message);
    },
    // onError
    (error) => {
        console.log(error.message);
    });
```