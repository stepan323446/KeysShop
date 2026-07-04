<?php

use KeysShop\Apps\Contacts\Controllers\ContactController;
use KeysShop\Includes\Routing\Path;

$contacts_urls = [
    new Path('/contacts', new ContactController(), 'form')
];
