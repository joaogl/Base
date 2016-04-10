<?php namespace jlourenco\base\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use jlourenco\base\Models\Logs;
use jlourenco\base\Models\Jobs;
use jlourenco\base\Models\Visits;
use Sentinel;
use Illuminate\Auth\Access\Response;

class BaseController extends Controller
{

    /**
     * Logs frontend
     *
     * @return View
     */
    public function getLogs()
    {
        // Show the page
        return View('admin.logs.list');
    }

    /**
     * Ajax call to get all the logs
     *
     * @return View
     */
    public function ajaxGetLogs()
    {
        return \Response::json(array('data' => Logs::orderBy('created_at', 'desc')->take(300)->get(['ip', 'log', 'created_at', 'created_by', 'target'])));
    }

    /**
     * Queues frontend
     *
     * @return View
     */
    public function getQueues()
    {
        // Show the page
        return View('admin.queues.list');
    }

    /**
     * Ajax call to get all the queues
     *
     * @return View
     */
    public function ajaxGetQueues()
    {
        $data = Jobs::orderBy('created_at', 'desc')->take(300)->get(['queue', 'payload', 'attempts', 'reserved', 'reserved_at', 'created_at']);

        foreach ($data as $d)
        {
            $obj = json_decode($d->payload);
            $d->payload = $obj->job;
            $d->data = json_encode($obj->data->data);
        }

        return \Response::json(array('data' => $data));
    }

    /**
     * Visits frontend
     *
     * @return View
     */
    public function getVisits()
    {
        // Show the page
        return View('admin.visits.list');
    }

    /**
     * Ajax call to get all the visits
     *
     * @return View
     */
    public function ajaxGetVisits()
    {
        $data = Visits::orderBy('created_at', 'desc')->take(300)->get(['url', 'browser', 'ip', 'created_at', 'country']);

        return \Response::json(array('data' => $data));
    }

}
