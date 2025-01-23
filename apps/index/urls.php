<?php
require_once APPS_PATH . '/index/controllers.php';

$index_urls = [
    new Path('', new HomepageController(), 'home'),
    new Path('/privacy', new PrivacyController(), 'privacy'),
    new Path('/terms', new TermsController(), 'terms'),
    new Path('/about', new AboutController(), 'about'),
    new Path('/faq', new FaqController(), 'faq'),
];
?>