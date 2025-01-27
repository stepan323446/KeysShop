<?php
require_once APPS_PATH . '/users/controllers.php';

$users_urls = [
    // Login and register
    new Path('/login', new LoginController(), 'login'),
    new Path('/logout', new LogoutController(), 'logout'),
    new Path('/register', new RegisterController(), 'register'),

    // Profile
    new Path('/user', new ProfileController(), 'profile'),
    new Path('/user/wishlist', new WishlistController(), 'wishlist'),
    new Path('/user/edit/information', new EditContactInfoController(), 'edit-info'),
    new Path('/user/edit/email', new EditEmailController(), 'edit-email'),
    new Path('/user/edit/password', new EditPasswordController(), 'edit-password'),
    
    // Reset password
    new Path('/forgot-password', new ForgotPasswordController(), 'forgot'),
    new Path('/reset-password/[:string]', new ResetPasswordController(), 'reset'),
];