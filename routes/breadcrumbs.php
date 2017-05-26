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

// Home > Daftar Tipe User
Breadcrumbs::register('role_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Tipe User', route('role_list'));
});

// Home > Daftar Tipe User > Role
Breadcrumbs::register('role', function($breadcrumbs)
{
    $breadcrumbs->parent('role_list');
    $breadcrumbs->push('Tipe User', route('role'));
});

// Home > Daftar Hak Akses
Breadcrumbs::register('permission_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Hak Akses', route('permission_list'));
});

// Home > Daftar Hak Akses > Permission
Breadcrumbs::register('permission', function($breadcrumbs)
{
    $breadcrumbs->parent('permission_list');
    $breadcrumbs->push('Hak Akses', route('permission'));
});

?>
