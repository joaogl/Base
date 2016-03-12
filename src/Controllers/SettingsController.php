<?php namespace jlourenco\base\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Base;

class SettingsController extends Controller
{

    /**
     * Show the settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movements = Base::getSettingsRepository()->all();

        return view('admin.settings.list', compact('movements'));
    }

    public function edit($id)
    {
        $movement = Base::getSettingsRepository()->findOrFail($id);

        return view('admin.settings.edit', compact('movement'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [ 'value' => 'required' ]);

        $move = Base::getSettingsRepository()->findOrFail($id);

        $move->update($request->all());

        return redirect('settings');
    }

}
