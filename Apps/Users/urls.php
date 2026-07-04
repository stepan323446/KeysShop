<?php

use Apps\Users\Controllers;
use Includes\Routing\Path;

$users_urls = [
    // Login and register
    new Path('/login', new Controllers\LoginController(), 'login'),
    new Path('/logout', new Controllers\LogoutController(), 'logout'),
    new Path('/register', new Controllers\RegisterController(), 'register'),

    // Profile
    new Path('/user', new Controllers\ProfileController(), 'profile'),
    new Path('/user/wishlist', new Controllers\WishlistController(), 'wishlist'),
    new Path('/user/orders', new Controllers\MyOrdersController(), 'orders'),
    new Path('/user/edit/information', new Controllers\EditContactInfoController(), 'edit-info'),
    new Path('/user/edit/email', new Controllers\EditEmailController(), 'edit-email'),
    new Path('/user/edit/password', new Controllers\EditPasswordController(), 'edit-password'),
    
    // Reset password
    new Path('/forgot-password', new Controllers\ForgotPasswordController(), 'forgot'),
    new Path('/reset-password/[:string]', new Controllers\ResetPasswordController(), 'reset'),
];