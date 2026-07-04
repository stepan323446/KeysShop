<?php

use KeysShop\Apps\Index\Controllers;
use KeysShop\Includes\Routing\Path;

$index_urls = [
    new Path('', new Controllers\HomepageController(), 'home'),
    new Path('/privacy', new Controllers\PrivacyController(), 'privacy'),
    new Path('/terms', new Controllers\TermsController(), 'terms'),
    new Path('/about', new Controllers\AboutController(), 'about'),
    new Path('/faq', new Controllers\FaqController(), 'faq'),
];
?>