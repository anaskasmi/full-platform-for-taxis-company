<?php

// Route to handle page reload in Vue except for api routes
Route::get('/{any?}','SinglePageController@index')->where('any', '^(?!api\/)[\/\w\.-]*');
// Route::get('/','SinglePageController@index');