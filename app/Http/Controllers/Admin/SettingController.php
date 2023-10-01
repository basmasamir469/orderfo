<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use App\Transformers\SettingTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    //
    public function update(SettingRequest $request)
    {
       $data=$request->validated();

       foreach ($data['settings'] as $key => $value) {
        if ($value instanceof UploadedFile) {
            $setting = Setting::where(['key' => $key])->first();
            $setting->clearMediaCollection('settings');
            $setting->addMedia($value)->toMediaCollection('settings');
        } else {
            Setting::where(['key' => $key])->update(['value' => $value]);
        }
    }

    Cache::forget('settings');
    return $this->dataResponse(null,'updated successfully',200);


    }
}
