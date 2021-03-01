<?php

use App\Middleware\AdminAuthMiddleware;
use App\Middleware\StudentAuthMiddleware;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    // cors
    $app->options('/{routes:.+}', function ($req, $res, $args) {
        return $res;
    });

    $app->get('[/]', \App\Action\HomeAction::class)->setName('home');

    //ADMIN no auth
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->get('[/[login[/]]]', \App\Action\Admin\LoginViewAction::class)->setName('admin-login');
        $group->post('/login[/]', \App\Action\Admin\LoginAction::class);
    });

    //ADMIN auth
    $app->group('/admin/', function (RouteCollectorProxy $group) {
    	//
        $group->get('dashboard', \App\Action\Admin\DashboardAction::class)->setName('a-dashboard');
        $group->get('logout', \App\Action\Admin\LogoutAction::class)->setName('a-logout');
	$group->get('reset[/]', \App\Action\Admin\ResetAction::class)->setName('reset');
	$group->post('reset[/]', \App\Action\Admin\ResetConfirmAction::class);
	// hostels
	$group->post('hostel[/]', \App\Action\Admin\Hostel\CreateAction::class)->setName('a-create-hostel');
	$group->get('hostel[/]', \App\Action\Admin\Hostel\IndexAction::class)->setName('a-hostel');
	$group->get('hostel/{id}/delete[/]', \App\Action\Admin\Hostel\DeleteAction::class)->setName('a-delete-hostel');

	// blocks
        $group->get('block[/]', \App\Action\Admin\Block\IndexAction::class)->setName('a-block');

	// rooms
        $group->get('room[/]', \App\Action\Admin\Room\IndexAction::class)->setName('a-room');
        $group->get('room/{id}[/]', \App\Action\Admin\Room\ViewAction::class)->setName('a-view-room');

	// block admins.
	$group->post('block-admin[/]', \App\Action\Admin\BlockAdmin\CreateAction::class)->setName('a-create-block-admin');
	$group->get('block-admin[/]', \App\Action\Admin\BlockAdmin\IndexAction::class)->setName('a-block-admin');
        $group->get('block-admin/{id}/delete[/]', \App\Action\Admin\BlockAdmin\DeleteAction::class)->setName('a-delete-block-admin');

    })->add(AdminAuthMiddleware::class);


    //student no auth
    $app->group('/student', function (RouteCollectorProxy $group) {
        $group->get('[/[login[/]]]', \App\Action\Student\LoginViewAction::class)->setName('student-login');
        $group->post('/login[/]', \App\Action\Student\LoginAction::class);
    });

    //Student auth
    $app->group('/student/', function (RouteCollectorproxy $group) {
        //
        $group->get('dashboard', \App\Action\Student\DashboardAction::class)->setName('s-dashboard');
        $group->get('logout', \App\Action\Student\LogoutAction::class)->setName('s-logout');

        // apply for hostel
        $group->post('apply[/]', \App\Action\Student\ApplyConfirmAction::class);
	$group->get('apply[/]', \App\Action\Student\ApplyAction::class)->setName('s-apply');

    })->add(StudentAuthMiddleware::class);

    // all
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};
