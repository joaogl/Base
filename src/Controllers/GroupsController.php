<?php namespace jlourenco\base\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Blog;
use Sentinel;
use Searchy;
use Validator;
use Input;
use Base;
use Redirect;
use Lang;

class GroupsController extends Controller
{

    /**
     * Declare the rules for the form validation
     *
     * @var array
     */
    protected $validationRules = array(
        'name'               => 'required|min:3',
        'slug'               => 'required|min:3|unique:Group,slug',
        'description'        => 'required|min:3',
    );

    /**
     * Show list of groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Sentinel::getRoleRepository()->all();

        return view('admin.groups.list', compact('groups'));
    }

    /**
     * Show details of a group,
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $group = Sentinel::getRoleRepository()->findOrFail($id);

        // Show the page
        return View('admin.groups.show', compact('group'));
    }

    /**
     * Group update.
     *
     * @param  int  $id
     * @return View
     */
    public function getEdit($id = null)
    {
        $group = Sentinel::getRoleRepository()->find($id);

        // Get the group's information
        if($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');

            // Redirect to the post management page
            return Redirect::route('groups')->with('error', $error);
        }

        $groups = null;

        $groups2 = Sentinel::getRoleRepository()->all(['id', 'name']);

        foreach ($groups2 as $g)
            $groups[$g->id] = $g->name;

        // Show the page
        return View('admin.groups.edit', compact('group', 'groups'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function postEdit($id = null)
    {
        // Get the post information
        $group = Sentinel::getRoleRepository()->find($id);

        if ($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');

            // Redirect to the post management page
            return Redirect::route('admin.blogs.show')->with('error', $error);
        }

        unset($this->validationRules['slug']);
        $this->validationRules['slug'] = "required|min:3|unique:Group,slug,{$group->slug},slug";

        $slug = str_slug(Input::get('name'), '_');

        $input = Input::all();
        $input['slug'] = $slug;

        // Create a new validator instance from our validation rules
        $validator = Validator::make($input, $this->validationRules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // Update the group
        $group->name = Input::get('name');
        $group->slug = $slug;
        $group->description = Input::get('description');

        // Was the post updated?
        if ($group->save())
        {
            Base::Log('Group (' . $group->id . ' - ' . $group->name . ') was edited.');

            // Prepare the success message
            $success = Lang::get('base.groups.changed');

            // Redirect to the user page
            return Redirect::route('groups')->with('success', $success);
        }

        $error = Lang::get('base.groups.error');

        // Redirect to the post page
        return Redirect::route('groups.update', $id)->withInput()->with('error', $error);
    }

    /**
     * Create new group
     *
     * @return View
     */
    public function getCreate()
    {
        $groups = null;

        $groups2 = Sentinel::getRoleRepository()->all(['id', 'name']);

        foreach ($groups2 as $g)
            $groups[$g->id] = $g->name;

        // Show the page
        return View('admin.groups.create', compact('groups'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function postCreate()
    {
        $slug = str_slug(Input::get('name'), '_');

        $input = Input::all();
        $input['slug'] = $slug;

        // Create a new validator instance from our validation rules
        $validator = Validator::make($input, $this->validationRules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $group = Sentinel::getRoleRepository()->findBySlug($slug);

        if ($group != null)
            return Redirect::route("groups")->with('error', Lang::get('base.groups.already_exists'));

        $group = Sentinel::getRoleRepository()->create([
            'name' => Input::get('name'),
            'slug' => $slug,
            'description' => Input::get('description'),
        ]);

        $group->save();

        Base::Log('A new group (' . $group->id . ' - ' . $group->name . ') was created.');

        // Redirect to the home page with success menu
        return Redirect::route("groups")->with('success', Lang::get('base.groups.created'));
    }

    /**
     * Delete Confirm
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id = null)
    {
        $confirm_route = $error = null;

        $title = 'Delete group';
        $message = 'Are you sure to delete this group?';

        // Get group information
        $group = Sentinel::getRoleRepository()->findOrFail($id);

        if ($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        $confirm_route = route('delete/group', ['id' => $group->id]);
        return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
    }

    /**
     * Delete the given group.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function getDelete($id = null)
    {
        // Get group information
        $group = Sentinel::getRoleRepository()->find($id);

        if ($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');

            // Redirect to the post management page
            return Redirect::route('groups')->with('error', $error);
        }

        Base::Log('Group (' . $group->id . ' - ' . $group->name . ') was deleted.');

        // Delete the group
        $group->delete();

        // Prepare the success message
        $success = Lang::get('base.groups.deleted');

        // Redirect to the post management page
        return Redirect::route('groups')->with('success', $success);
    }

}
