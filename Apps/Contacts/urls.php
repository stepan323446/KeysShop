<?php

use Apps\Contacts\Controllers\ContactController;
use Includes\Routing\Path;

$contacts_urls = [
    new Path('/contacts', new ContactController(), 'form')
];
