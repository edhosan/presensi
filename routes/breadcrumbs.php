<?php
// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Home > Daftar User
Breadcrumbs::register('user', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar User', route('user'));
});

// Home > Daftar User > Register
Breadcrumbs::register('register', function($breadcrumbs)
{
    $breadcrumbs->parent('user');
    $breadcrumbs->push('Register', route('register'));
});

?>
