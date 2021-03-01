<?php

namespace App\Action\Admin;

use App\Domain\Service\Room;
use App\Domain\Service\Student;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

final class ResetConfirmAction
{
    /**
     * @var Session
     */
    private $session;
    private $room;
    private $student;

    public function __construct(Session $session, Room $room, Student $student)
    {
        $this->session = $session;
        $this->room = $room;
	$this->student = $student;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

	$student = $this->student->readAll();

	foreach($student as $s)
	    $this->student->update($s->id, ['room'=>'', 'room_id' => 0]);

	$room = $this->room->readAll();
	foreach($room as $r)
            $this->room->update($r->id, ['used'=> '0']);

        // Clear all flash messages
        $flash = $this->session->getFlashBag();
        $flash->clear();

        // Get RouteParser from request to generate the urls
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $flash->set('success', 'Reset successfully');

        // Redirect to protected page
        $url = $routeParser->urlFor('reset');

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
