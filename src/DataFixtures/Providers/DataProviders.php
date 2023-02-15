<?php

namespace App\DataFixtures\Providers;

class DataProviders
{
    private $users = [
        [
            'email'     => 'jeanne@test.com',
            'password'  => 'test',
            'firstname' => 'Jeanne',
            'lastname'  => 'Smith',
            'role'      => 'ROLE_USER',
            'phone'     => '0123456789'
        ],
        [
            'email'     => 'test@test.com',
            'password'  => 'test',
            'firstname' => 'Test',
            'lastname'  => 'Test',
            'role'      => 'ROLE_TEST',
            'phone'     => '0123456789'
        ],
        [
            'email'     => 'admin@test.com',
            'password'  => 'test',
            'firstname' => 'Admin',
            'lastname'  => 'Admin',
            'role'      => 'ROLE_ADMIN',
            'phone'     => '0123456789'
        ],
    ];

    private $pages = [
        'home',
        'gourmand surprise',
        'menu',
        'gallery',
        'news',
        'contact',
        'client-space'
    ];

    private $languages = [
        'fr',
        'en'
    ];

    /**
     * Get the value of users
     */ 
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get the value of pages
     */ 
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Get the value of languages
     */ 
    public function getLanguages()
    {
        return $this->languages;
    }
}