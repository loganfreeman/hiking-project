<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\BootstrapCMS\Http\Controllers;

use Carbon\Carbon;
use GrahamCampbell\Binput\Facades\Binput;
use GrahamCampbell\BootstrapCMS\Facades\EventRepository;
use GrahamCampbell\Credentials\Facades\Credentials;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Input;
use GrahamCampbell\BootstrapCMS\Models\EventSignups;
use Illuminate\Support\Facades\Response;
use Exception;



/**
 * This is the event controller class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class EventController extends AbstractController
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setPermissions([
            'create'  => 'edit',
            'store'   => 'edit',
            'edit'    => 'edit',
            'update'  => 'edit',
            'destroy' => 'edit',
        ]);

        parent::__construct();
    }

    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $events = EventRepository::paginate();
        $links = EventRepository::links();

        return View::make('events.index', ['events' => $events, 'links' => $links]);
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return View::make('events.create');
    }

    /**
     * Store a new event.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $user = Credentials::getuser();
        $input = array_merge(['user_id' => $user->id], Binput::only([
            'title', 'location', 'date', 'body',
        ]));

        $val = EventRepository::validate($input, array_keys($input));
        if ($val->fails()) {
            return Redirect::route('events.create')->withInput()->withErrors($val->errors());
        }

        $input['date'] = Carbon::createFromFormat(Config::get('date.php_format'), $input['date']);

        $event = EventRepository::create($input);

        return Redirect::route('events.show', ['events' => $event->id, 'user' => $user])
            ->with('success', trans('messages.event.store_success'));
    }

    /**
     * Show the specified event.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $event = EventRepository::find($id);
        $this->checkEvent($event);
        $user = Credentials::getuser();

        return View::make('events.show', ['event' => $event, 'user' => $user]);
    }

    public function isSignupedbyme($id)
    {
      if(is_null($id)) {
        throw new Exception('No event ID provided');
      }
      $event = EventRepository::find($id);
      if(is_null($event)) {
        throw new Exception('No event found with ID ' . $id);
      }
      $data = false;
      $userId = Credentials::getuser()->id;
      if (EventSignups::whereUserId($userId)->whereEventId($event->id)->exists()){
          $data = true;
      }
      return Response::json($data);
    }


    public function signup()
    {
        $id = Input::get('id');
        $event = EventRepository::find($id);
        $userId = Credentials::getuser()->id;
        $existing_signup = EventSignups::whereEventId($event->id)->whereUserId($userId)->first();

        if (is_null($existing_signup)) {
            $existing_signup = EventSignups::create([
                'event_id' => $event->id,
                'user_id' => $userId
            ]);
        } else {
            if (is_null($existing_signup->deleted_at)) {
                $existing_signup->delete();
            } else {
                $existing_signup->restore();
            }
        }

        return Response::json($existing_signup);
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $event = EventRepository::find($id);
        $this->checkEvent($event);

        return View::make('events.edit', ['event' => $event]);
    }

    /**
     * Update an existing event.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $input = Binput::only(['title', 'location', 'date', 'body']);
        $user = Credentials::getuser();

        $val = $val = EventRepository::validate($input, array_keys($input));
        if ($val->fails()) {
            return Redirect::route('events.edit', ['events' => $id])->withInput()->withErrors($val->errors());
        }

        $input['date'] = Carbon::createFromFormat(Config::get('date.php_format'), $input['date']);

        $event = EventRepository::find($id);
        $this->checkEvent($event);

        $event->update($input);

        return Redirect::route('events.show', ['events' => $event->id, 'user' => $user])
            ->with('success', trans('messages.event.update_success'));
    }

    /**
     * Delete an existing event.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = EventRepository::find($id);
        $this->checkEvent($event);

        $event->delete();

        return Redirect::route('events.index')
            ->with('success', trans('messages.event.delete_success'));
    }

    /**
     * Check the event model.
     *
     * @param mixed $event
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function checkEvent($event)
    {
        if (!$event) {
            throw new NotFoundHttpException('Event Not Found');
        }
    }
}
