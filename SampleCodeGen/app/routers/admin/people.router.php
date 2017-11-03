<?php
$app->group('/admin/people/', function () {
    $this->get('view', 'App\Controller\PeopleController:View')->setName('people-view');
    $this->get('details/{id}', 'App\Controller\PeopleController:Details')->setName('people-details');
    $this->get('export', 'App\Controller\PeopleController:Export')->setName('people-export');
    $this->post('viewpro', 'App\Controller\PeopleController:ViewPro')->setName('people-viewpro');
    $this->get('add', 'App\Controller\PeopleController:Add')->setName('people-add');
    $this->post('addpro', 'App\Controller\PeopleController:AddPro')->setName('people-addpro');
    $this->get('update/{id}', 'App\Controller\PeopleController:Update')->setName('people-update');
    $this->post('updatepro', 'App\Controller\PeopleController:UpdatePro')->setName('people-updatepro');
    $this->get('delete', 'App\Controller\PeopleController:Delete')->setName('people-delete');
})->add(\App\Model\HTSAuth::class);
        