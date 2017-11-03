<?php
    $container['App\Controller\PeopleController'] = function ($c) {
        return new App\Controller\PeopleController($c);
    };
               