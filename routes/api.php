<?php

//fetch TC data
Route::get('/clear-facade', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//clearing routes
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
//Clear Config cache:
Route::get('/routeList', function () {
    $exitCode = Artisan::call('route:list');
    dd(Artisan::output());
    return '<h1>'.$exitCode.'</h1>';
});
///////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////// DOWNLOADABLES //////////////////////////////////////
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {
    Route::get('loadTrips', 'TaxiCaller@getTripsFromJsonFile')->name('TaxiCaller.getTripsFromJsonFile');
    Route::get('deleteLoadedTrips', 'TaxiCaller@deleteInsertedTrips')->name('TaxiCaller.deleteInsertedTrips');
    // downloadables
    Route::get('posSlips/download/{batch_id}', 'PosSlipsController@download')->name('posSlips.download');
    Route::get('Envelopes/download/{batch_id}', 'EnvelopesController@download')->name('Envelopes.download');
    Route::get('taxiSavers/download/{batch_id}', 'TaxiSaversController@download')->name('Envelopes.download');
    Route::prefix('/AccountSlips')->name('AccountSlips.')->group(function () {
        Route::get('download/{batch_id}', 'AccountSlipsController@download')->name('AccountSlips.download');
        Route::get('generatePDFs/{batch_id}', 'AccountSlipsController@generatePDFs')->name('Envelopes.generatePDFs');
    });
});

//admin routes
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {
    //login route
    Route::post('auth/login', 'AuthController@login');
    //reports download links
    //daily log sheet

    Route::get('reports/DailyLogSheet/{badgeId}/printPDF/{date}', 'DailyLogSheetController@printPDF')->name('reports.DailyLogSheet.printPDF');
    Route::get('reports/currentMonth/{badgeId}/printPDF', 'DailyLogSheetController@currentMonth_pdf')->name('reports.currentMonth.printPDF');
    Route::get('reports/pastMonth/{badgeId}/printPDF', 'DailyLogSheetController@pastMonth_pdf')->name('reports.pastMonth.printPDF');
    Route::get('reports/currentYear/{badgeId}/printPDF', 'DailyLogSheetController@currentYear_pdf')->name('reports.currentYear.printPDF');
    Route::get('reports/pastYear/{badgeId}/printPDF', 'DailyLogSheetController@pastYear_pdf')->name('reports.pastYear.printPDF');
    //WeeklyTaxiShifts
    Route::get('reports/WeeklyTaxiShifts/printPDF/{date}', 'WeeklyTaxiShiftsController@printPDF')->name('reports.WeeklyTaxiShifts.printPDF');
    //WeeklyDriverHours
    Route::get('reports/WeeklyDriverHours/printPDF/{date}', 'WeeklyDriverHoursController@printPDF')->name('reports.WeeklyDriverHours.printPDF');
    //test

    Route::group(['middleware' => 'apiAuth:admin'], function () {

        //auth
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');
        Route::post('auth/changePassword', 'AuthController@changePassword');

        //drivers
        //CRUD
        Route::get('drivers', 'DriversController@index')->name('drivers.index');
        Route::get('driver/{id}', 'DriversController@show')->name('drivers.show');
        Route::post('driver', 'DriversController@store')->name('drivers.store');
        Route::PATCH('driver/{id}', 'DriversController@update')->name('drivers.update');
        Route::delete('driver/{id}', 'DriversController@destroy')->name('drivers.delete');
        //Search
        Route::get('drivers/search', 'DriversController@search')->name('drivers.search');

        //directors
        //CRUD
        Route::get('directors', 'DirectorsController@index')->name('directors.index');
        Route::get('director/{id}', 'DirectorsController@show')->name('directors.show');
        Route::post('director', 'DirectorsController@store')->name('directors.store');
        Route::PATCH('director/{id}', 'DirectorsController@update')->name('directors.update');
        Route::delete('director/{id}', 'DirectorsController@destroy')->name('directors.delete');
        //Search
        Route::get('directors/search', 'DirectorsController@search')->name('directors.search');

        //dispatchers
        //CRUD
        Route::get('dispatchers', 'DispatchersController@index')->name('dispatchers.index');
        Route::get('dispatcher/{id}', 'DispatchersController@show')->name('dispatchers.show');
        Route::post('dispatcher', 'DispatchersController@store')->name('dispatchers.store');
        Route::PATCH('dispatcher/{id}', 'DispatchersController@update')->name('dispatchers.update');
        Route::delete('dispatcher/{id}', 'DispatchersController@destroy')->name('dispatchers.delete');
        //Search
        Route::get('dispatchers/search', 'DispatchersController@search')->name('dispatchers.search');

        //mlds
        //CRUD
        Route::get('mlds', 'MLDsController@index')->name('mlds.index');
        Route::get('mld/{id}', 'MLDsController@show')->name('mlds.show');
        Route::post('mld', 'MLDsController@store')->name('mlds.store');
        Route::PATCH('mld/{id}', 'MLDsController@update')->name('mlds.update');
        Route::delete('mld/{id}', 'MLDsController@destroy')->name('mlds.delete');
        //Search
        Route::get('mlds/search', 'MLDsController@search')->name('mlds.search');

        //trips
        //CRUD
        Route::get('trips', 'TripsController@index')->name('trips.index');
        Route::get('trip/{id}', 'TripsController@show')->name('trips.show');
        Route::post('trip', 'TripsController@store')->name('trips.store');
        Route::PATCH('trip/{id}', 'TripsController@update')->name('trips.update');
        Route::delete('trip/{id}', 'TripsController@destroy')->name('trips.delete');
        //Search
        Route::get('trips/search', 'TripsController@search')->name('trips.search');

        //archive
        Route::get('archives/search/', 'ArchivesController@search')->name('archives.search');
        Route::get('archives/{id}', 'ArchivesController@show')->name('archives.show');
        Route::get('archives/', 'ArchivesController@index')->name('archives.index');
        Route::delete('archives/{id}', 'ArchivesController@destroy')->name('archives.delete');

        //Reports

        //DailyLogSheet
        // Route::get('reports/DailyLogSheet/{badgeId}/printPDF/{date}', 'DailyLogSheetController@printPDF')->name('reports.DailyLogSheet.printPDF');
        Route::get('reports/DailyLogSheet/{badgeId}', 'DailyLogSheetController@datePicker')->name('reports.DailyLogSheet.datePicker');
        Route::post('reports/DailyLogSheet/day/{badgeId}', 'DailyLogSheetController@DailyLogSheet')->name('reports.DailyLogSheet.day');
        Route::get('reports/DailyLogSheet/currentMonth/{badgeId}', 'DailyLogSheetController@currentMonth')->name('reports.DailyLogSheet.currentMonth');
        Route::get('reports/DailyLogSheet/pastMonth/{badgeId}', 'DailyLogSheetController@pastMonth')->name('reports.DailyLogSheet.pastMonth');
        Route::get('reports/DailyLogSheet/currentYear/{badgeId}', 'DailyLogSheetController@currentYear')->name('reports.DailyLogSheet.currentYear');
        Route::get('reports/DailyLogSheet/pastYear/{badgeId}', 'DailyLogSheetController@pastYear')->name('reports.DailyLogSheet.pastYear');

        //WeeklyTaxiShifts
        // Route::get('reports/WeeklyTaxiShifts/printPDF/{date}', 'WeeklyTaxiShiftsController@printPDF')->name('reports.WeeklyTaxiShifts.printPDF');
        Route::get('reports/WeeklyTaxiShifts/list', 'WeeklyTaxiShiftsController@WeeklyTaxiShifts')->name('reports.WeeklyTaxiShifts.WeeklyTaxiShifts');

        //WeeklyDriverHours
        // Route::get('reports/WeeklyDriverHours/printPDF/{date}', 'WeeklyDriverHoursController@printPDF')->name('reports.WeeklyDriverHours.printPDF');
        Route::get('reports/WeeklyDriverHours/list', 'WeeklyDriverHoursController@WeeklyDriverHours')->name('reports.WeeklyDriverHours.WeeklyDriverHours');

        //specialShifts
        //CRUD
        Route::get('specialShifts', 'SpecialShiftsController@index')->name('specialShifts.index');
        Route::get('specialShift/{id}', 'SpecialShiftsController@show')->name('specialShifts.show');
        Route::post('specialShift', 'SpecialShiftsController@store')->name('specialShifts.store');
        Route::put('specialShift/{id}', 'SpecialShiftsController@update')->name('specialShifts.update');
        Route::delete('specialShift/{id}', 'SpecialShiftsController@destroy')->name('specialShifts.delete');
        //Search
        Route::get('specialShifts/search', 'SpecialShiftsController@search')->name('specialShifts.search');

        //batches Crud
        Route::get('batches/{year}', 'BatchesController@all')->name('batches.all');
        Route::get('batch/{id}', 'BatchesController@show')->name('batches.show');
        Route::post('batch', 'BatchesController@store')->name('batches.store');
        Route::PATCH('batch/{id}', 'BatchesController@update')->name('batches.update');
        Route::delete('batch/{id}', 'BatchesController@destroy')->name('batches.delete');
        //get new trips
        Route::get('batch/getNewTrips/{id}', 'BatchesController@getTripsToCurrentBatch')->name('batches.getNewTrips');

        //Envelopes Crud
        Route::get('Envelopes/{batch_id}', 'EnvelopesController@all')->name('Envelopes.all');
        Route::get('Envelope/summary/{id}', 'EnvelopesController@summary')->name('Envelopes.show');
        Route::get('Envelope/{id}', 'EnvelopesController@show')->name('Envelopes.show');
        Route::post('Envelope', 'EnvelopesController@store')->name('Envelopes.store');
        Route::PATCH('Envelope/{id}', 'EnvelopesController@update')->name('Envelopes.update');
        Route::delete('Envelope/{id}', 'EnvelopesController@destroy')->name('Envelopes.delete');
        //pos Slips Crud
        Route::get('posSlips/{batch_id}/{envelope_id}', 'PosSlipsController@all')->name('posSlips.all');
        Route::get('posSlip/{id}', 'PosSlipsController@show')->name('posSlips.show');
        Route::post('posSlip', 'PosSlipsController@store')->name('posSlips.store');
        Route::PATCH('posSlip/{id}', 'PosSlipsController@update')->name('posSlips.update');
        Route::delete('posSlip/{id}', 'PosSlipsController@destroy')->name('posSlips.delete');
        //taxi savers Crud
        Route::get('TaxiSavers/{batch_id}/{envelope_id}', 'TaxiSaversController@all')->name('TaxiSavers.all');
        Route::get('TaxiSaver/{id}', 'TaxiSaversController@show')->name('TaxiSavers.show');
        Route::post('TaxiSaver', 'TaxiSaversController@store')->name('TaxiSavers.store');
        Route::PATCH('TaxiSaver/{id}', 'TaxiSaversController@update')->name('TaxiSavers.update');
        Route::delete('TaxiSaver/{id}', 'TaxiSaversController@destroy')->name('TaxiSavers.delete');
        //AccountSlips Crud
        Route::get('AccountSlips_search/search', 'AccountSlipsController@search')->name('AccountSlips.search');
        Route::get('AccountSlips/{batch_id}/{envelope_id}', 'AccountSlipsController@all')->name('AccountSlips.all');
        Route::get('AccountSlip/{id}', 'AccountSlipsController@show')->name('AccountSlips.show');
        Route::post('AccountSlip', 'AccountSlipsController@store')->name('AccountSlips.store');
        Route::PATCH('AccountSlip/{id}', 'AccountSlipsController@update')->name('AccountSlips.update');
        Route::delete('AccountSlip/{id}', 'AccountSlipsController@destroy')->name('AccountSlips.delete');
        Route::get('importTrip/{job_id}/{batch_id}', 'AccountSlipsController@importTrip')->name('AccountSlips.importTrip');
        Route::get('/fixImageId', 'AccountSlipsController@fixImageIdOfThisBatch');
        //taxi caller
        Route::get('/taxicaller', 'TaxiCaller@init')->name('TaxiCaller.fetchData');
        Route::get('/deleteTripsOfMonth', 'TaxiCaller@deleteTripsOfMonth')->name('TaxiCaller.deleteTripsOfMonth');
        Route::get('/parse', 'TaxiCaller@parseCSVtoJson');


    });

});

//Driver routes
Route::prefix('/driver')->name('driver.')->namespace('Driver')->group(function () {

    //auth
    Route::post('auth/login', 'AuthController@login');
    //reports download links
    //daily log sheet
    Route::get('reports/DailyLogSheet/{badgeId}/printPDF/{date}', 'DailyLogSheetController@printPDF')->name('reports.DailyLogSheet.printPDF');
    Route::get('reports/currentMonth/{badgeId}/printPDF', 'DailyLogSheetController@currentMonth_pdf')->name('reports.currentMonth.printPDF');
    Route::get('reports/pastMonth/{badgeId}/printPDF', 'DailyLogSheetController@pastMonth_pdf')->name('reports.pastMonth.printPDF');
    Route::get('reports/currentYear/{badgeId}/printPDF', 'DailyLogSheetController@currentYear_pdf')->name('reports.currentYear.printPDF');
    Route::get('reports/pastYear/{badgeId}/printPDF', 'DailyLogSheetController@pastYear_pdf')->name('reports.pastYear.printPDF');
    Route::get('reports/WeeklyDriverHours/{badgeId}/printPDF/{date}', 'WeeklyDriverHoursController@printPDF')->name('reports.WeeklyDriverHours.printPDF');

    Route::group(['middleware' => 'apiAuth:driver'], function () {

        //Auth
        Route::post('auth/logout', 'AuthController@logout');
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/changePassword', 'AuthController@changePassword');

        //trips
        //CRUD
        Route::get('trips', 'TripsController@index')->name('trips.index');
        Route::get('trip/{trip_id}', 'TripsController@show')->name('trips.show');
        Route::post('trip', 'TripsController@store')->name('trips.store');
        Route::PATCH('trip/{trip_id}', 'TripsController@update')->name('trips.update');
        Route::delete('trip/{trip_id}', 'TripsController@destroy')->name('trips.delete');
        //Search
        Route::get('trips/search', 'TripsController@search')->name('trips.search');

        //Reports
        //DailyLogSheet
        Route::post('reports/DailyLogSheet/day', 'DailyLogSheetController@DailyLogSheet')->name('reports.DailyLogSheet.day');
        Route::get('reports/DailyLogSheet/currentMonth', 'DailyLogSheetController@currentMonth')->name('reports.DailyLogSheet.currentMonth');
        Route::get('reports/DailyLogSheet/currentMonthShifts', 'DailyLogSheetController@currentMonthShifts')->name('reports.WeeklyDriverHours.currentMonthShifts');
        Route::get('reports/DailyLogSheet/pastMonthShifts', 'DailyLogSheetController@pastMonthShifts')->name('reports.WeeklyDriverHours.pastMonthShifts');
        Route::get('reports/DailyLogSheet/pastYear', 'DailyLogSheetController@pastYear')->name('reports.DailyLogSheet.pastYear');
        Route::get('reports/DailyLogSheet/currentYear', 'DailyLogSheetController@currentYear')->name('reports.DailyLogSheet.currentYear');
        //WeeklyDriverHours
        Route::get('reports/WeeklyDriverHours', 'WeeklyDriverHoursController@WeeklyDriverHours')->name('reports.WeeklyDriverHours.WeeklyDriverHours');

        //shifts
        //CRUD
        // Route::get('shifts', 'ShiftsController@index')->name('shifts.index');
        Route::get('shiftsOfMonth/{month}/{year}', 'ShiftsController@shiftsOfMonth')->name('shifts.shiftsOfMonth');
        Route::get('shift/{shift_id}', 'ShiftsController@getTripsOfShift')->name('shifts.getTripsOfShift');
        //rotations
        Route::get('rotation/{id}', 'RotationsController@show')->name('RotationsCategorys.show');
        Route::get('rotationsCategory/{id}', 'RotationsController@rotationsByCategory')->name('rotation.rotationsCategory');
        Route::get('rotationsByVehicle/{id}', 'RotationsController@rotationsByVehicle')->name('rotation.rotationsByVehicle');
        Route::get('rotationsByVehicleAndCategory/{category_id}/{vehicle_id}', 'RotationsController@rotationsByVehicleAndCategory')->name('rotation.rotationsByVehicleAndCategory');
        //vehicles
        Route::get('vehicles', 'VehiclesController@vehicles')->name('vehicles.vehicles');
        Route::get('vehiclesWithNames', 'VehiclesController@vehiclesWithNames')->name('vehicles.vehiclesWithNames');
        Route::post('authenticateVehicle', 'VehiclesController@authenticateVehicle')->name('vehicles.authenticateVehicle');
        //rotationsCategories
        Route::get('rotationsCategories', 'RotationsCategoriesController@rotationsCategories')->name('RotationsCategorys.RotationsCategorys');

        //voice messages
        Route::post('voiceMessage', 'letsTalkController@storeVoiceMessage')->name('letsTalkController.storeVoiceMessage');
        Route::get('voiceMessages/templates', 'letsTalkController@getVoiceMessagesTemplates')->name('letsTalkController.getVoiceMessagesTemplates');
        Route::get('voiceMessages/newVoiceMessages/{id}', 'letsTalkController@getNewVoiceMessages')->name('letsTalkController.getVoiceMessagesTemplates');
        Route::get('voiceMessages/download/{id}', 'letsTalkController@downloadVoiceMessage')->name('letsTalkController.downloadVoiceMessage');
        // Route::get('voiceMessage/{trip_id}', 'TripsController@show')->name('trips.show');
        // Route::PATCH('voiceMessage/{trip_id}', 'TripsController@update')->name('trips.update');
        // Route::delete('voiceMessage/{trip_id}', 'TripsController@destroy')->name('trips.delete');


        //PreInspection routes
        Route::post('storePreInspection', 'PreInspectionsController@store')->name('PreInspectionsController.store');


    });

});

//director routes
Route::prefix('/director')->name('director.')->namespace('Director')->group(function () {

    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'apiAuth:director'], function () {
        //auth
        Route::post('auth/logout', 'AuthController@logout');
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/changePassword', 'AuthController@changePassword');

        //dispatchers
        //CRUD
        Route::get('comments', 'CommentsController@index')->name('comments.index');
        Route::get('comment/{id}', 'CommentsController@show')->name('comments.show');
        Route::post('comment', 'CommentsController@store')->name('comments.store');
        Route::PATCH('comment/{id}', 'CommentsController@update')->name('comments.update');
        Route::delete('comment/{id}', 'CommentsController@destroy')->name('comments.delete');
        //Search
        Route::get('comments/search', 'CommentsController@search')->name('comments.search');

        //WeeklyTaxiShifts
        Route::get('reports/WeeklyTaxiShifts/list', 'WeeklyTaxiShiftsController@WeeklyTaxiShifts')->name('reports.WeeklyTaxiShifts.WeeklyTaxiShifts');

    });

});

//dispatcher routes
Route::prefix('/dispatcher')->name('dispatcher.')->namespace('Dispatcher')->group(function () {

    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'apiAuth:dispatcher'], function () {
        //auth
        Route::post('auth/logout', 'AuthController@logout');
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/changePassword', 'AuthController@changePassword');

        //drivers
        //CRUD
        Route::get('drivers', 'DriversController@index')->name('drivers.index');
        Route::get('driver/{id}', 'DriversController@show')->name('drivers.show');
        Route::post('driver', 'DriversController@store')->name('drivers.store');
        Route::PATCH('driver/{id}', 'DriversController@update')->name('drivers.update');
        Route::delete('driver/{id}', 'DriversController@destroy')->name('drivers.delete');
        //Search
        Route::get('drivers/search', 'DriversController@search')->name('drivers.search');

        //specialShifts
        //CRUD
        Route::get('specialShifts', 'SpecialShiftsController@specialShifts')->name('specialShifts.specialShifts');
        Route::get('mySpecialShifts', 'SpecialShiftsController@mySpecialShifts')->name('specialShifts.mySpecialShifts');
        Route::get('specialShift/{id}', 'SpecialShiftsController@show')->name('specialShifts.show');
        Route::post('specialShift', 'SpecialShiftsController@store')->name('specialShifts.store');
        Route::put('specialShift/{id}', 'SpecialShiftsController@update')->name('specialShifts.update');
        Route::delete('specialShift/{id}', 'SpecialShiftsController@destroy')->name('specialShifts.delete');
        //Search
        Route::get('specialShifts/search', 'SpecialShiftsController@search')->name('specialShifts.search');
        Route::get('specialShifts/searchMySpecialShifts', 'SpecialShiftsController@searchMySpecialShifts')->name('specialShifts.searchMySpecialShifts');

        //accountTypes
        //CRUD
        Route::get('accountTypes', 'accountTypesController@accountTypes')->name('accountTypes.accountTypes');
        Route::get('accountType/{id}', 'accountTypesController@show')->name('accountTypes.show');
        Route::post('accountType', 'accountTypesController@store')->name('accountTypes.store');
        Route::put('accountType/{id}', 'accountTypesController@update')->name('accountTypes.update');
        Route::delete('accountType/{id}', 'accountTypesController@destroy')->name('accountTypes.delete');

        //authorization Files
        //CRUD
        Route::get('authorizationFiles', 'authorizationFilesController@authorizationFiles')->name('authorizationFiles.authorizationFiles');
        Route::get('authorizationFile/{id}', 'authorizationFilesController@show')->name('authorizationFiles.show');
        Route::post('authorizationFile', 'authorizationFilesController@store')->name('authorizationFiles.store');
        Route::put('authorizationFile/{id}', 'authorizationFilesController@update')->name('authorizationFiles.update');
        Route::delete('authorizationFile/{id}', 'authorizationFilesController@destroy')->name('authorizationFiles.delete');
        //Search
        Route::get('authorizationFiles/search', 'authorizationFilesController@search')->name('authorizationFiles.search');

        //rotationsCategories
        //CRUD
        Route::get('rotationsCategories', 'RotationsCategoriesController@rotationsCategories')->name('RotationsCategorys.RotationsCategorys');
        Route::get('rotationsCategory/{id}', 'RotationsCategoriesController@show')->name('RotationsCategorys.show');
        Route::post('rotationsCategory', 'RotationsCategoriesController@store')->name('RotationsCate    gorys.store');
        Route::put('rotationsCategory/{id}', 'RotationsCategoriesController@update')->name('RotationsCategorys.update');
        Route::delete('rotationsCategory/{id}', 'RotationsCategoriesController@destroy')->name('RotationsCategorys.delete');
        //rotations
        //CRUD
        //calculate total marks
        Route::get('rotations/calculateMarks', 'RotationsController@recalculateAllVehiclesMarks')->name('rotation.rotations');


        Route::get('rotations', 'RotationsController@rotations')->name('rotation.rotations');
        Route::get('rotation/{id}', 'RotationsController@show')->name('RotationsCategorys.show');
        Route::post('rotation', 'RotationsController@store')->name('rotation.store');
        Route::put('rotation/{id}', 'RotationsController@update')->name('rotation.update');
        Route::delete('rotation/{id}', 'RotationsController@destroy')->name('rotation.delete');
//        Route::get('rotationsCategory/{id}', 'RotationsController@rotationsByCategory')->name('rotation.rotationsCategory');
        Route::get('rotationsByVehicle/{id}', 'RotationsController@rotationsByVehicle')->name('rotation.rotationsByVehicle');
        Route::get('rotationsByVehicleAndCategory/{category_id}/{vehicle_id}', 'RotationsController@rotationsByVehicleAndCategory')->name('rotation.rotationsByVehicleAndCategory');
        //Search
        Route::get('rotations/search', 'RotationsController@search')->name('rotations.search');

        //cities
        //CRUD
        Route::get('cities', 'CitiesController@cities')->name('cities.cities');
        Route::get('city/{id}', 'CitiesController@show')->name('cities.show');
        Route::post('city', 'CitiesController@store')->name('cities.store');
        Route::put('city/{id}', 'CitiesController@update')->name('cities.update');
        Route::delete('city/{id}', 'CitiesController@destroy')->name('cities.delete');
        //vehicles
        //CRUD
        Route::get('vehicles', 'VehiclesController@vehicles')->name('vehicles.vehicles');
        Route::get('vehicle/{id}', 'VehiclesController@show')->name('vehicles.show');
        Route::post('vehicle', 'VehiclesController@store')->name('vehicles.store');
        Route::put('vehicle/{id}', 'VehiclesController@update')->name('vehicles.update');
        Route::delete('vehicle/{id}', 'VehiclesController@destroy')->name('vehicles.delete');
        Route::get('vehicles/resetVehiclesPassword/{id}', 'VehiclesController@resetVehiclesPassword')->name('vehicles.resetVehiclesPassword');
    });

});
