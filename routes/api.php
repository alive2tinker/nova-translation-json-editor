<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/locales', function (Request $request) {
    $localesArr = [];
    //list all localization files
    $dir = base_path() . "/resources/lang/";
    $locales = glob("$dir*.json");
    foreach($locales as $locale){
        $localesArr[] = [
            'name' => substr($locale, strlen($dir), strlen($locale)),
            'strings' => json_decode(file_get_contents($locale), true)
        ];
    }

    return response()->json($localesArr, 200);
});

Route::post('/updateLocales', function(Request $request){
    $nuLocales = json_decode($request->input('locales'), true);
    foreach($nuLocales as $nuLocale){
        $myfile = fopen(base_path() . "/resources/lang/{$nuLocale['name']}", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($nuLocale['strings']));
        fclose($myfile);
    }
    return response()->json([], 200);
});
