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
        $settings = Base::getSettingsRepository()->all();

        return view('admin.settings.list', compact('settings'));
    }

    public function edit($id)
    {
        $setting = Base::getSettingsRepository()->findOrFail($id);

        return view('admin.settings.edit', compact('setting'));
    }

    public function  update($id, Request $request)
    {
        $this->validate($request, [ 'value' => 'required' ]);

        $move = Base::getSettingsRepository()->findOrFail($id);

        $before = $move->value;

        $move->update($request->all());

        $after = $move->value;

        Base::Log('Website settings changed. Changed "' . $move->friendly_name . '" value from "' . $before . '" to "' . $after . '"');

        return redirect('admin/settings');
    }

}
