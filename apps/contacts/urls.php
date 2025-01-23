<?php
require_once APPS_PATH . '/contacts/controllers.php';

$contacts_urls = [
    new Path('/contacts', new ContactController(), 'form')
];
