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
* Project structure
* MVC (Model-View-Controller)
    * BaseModel
    * BaseController and Page Rendering
    * [Router](#router)
* [Ajax](#ajax)

## Libraries

The website is built entirely from scratch using pure PHP, without relying on any frameworks. This was done as an experimental project to enhance personal skills. Despite this, some libraries were used to add specific functionality:

- [\[PHP\] PHPMailer](https://github.com/PHPMailer/PHPMailer): A library for sending emails, used for password recovery functionality.
- [\[JS\] Swiper.js](https://github.com/nolimits4web/swiper): A highly customizable library for creating sliders and carousels.
- [\[JS\] Toastify.js](https://github.com/apvarun/toastify-js): A lightweight library for creating beautiful and customizable toast notifications.

## Router and URL Handling

The main `Router` class is located at `includes/router.php`. This class serves as the core for routing and displaying the appropriate controller based on the path.

### Classes Overview

- **`Router`**: The primary class used for determining which controller to render based on the requested path.
- **`Path`**: A helper class where the path to a controller and the link name are specified.
- **Exception Classes**: Used for handling errors, including:
  - `PageError` (general, 500)
  - `PermissionDeniedHttp403`
  - `UnauthorizedHttp401`
  - `NotFoundHttp404`

### Defining Routes

In mini-applications, routes are defined in a `urls.php` file, where paths and controllers are specified. Variables like `slug` or `id` can be passed using placeholders such as `[:string]` or `[:int]`.

```php
require_once APPS_PATH . '/my_app/controllers.php';

$my_app_urls = [
    // path, controller, name
    new Path('/item/[:string]', new SingleController(), 'single'),
    new Path('/home', new HomeController(), 'home'),
];
```

**Integrating Routes into the Main Application**

The array of mini-application routes is then added to the root directory's urls.php file, specifying the group name. Additionally, error controllers (e.g., "default", 404, 500) can also be defined:

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