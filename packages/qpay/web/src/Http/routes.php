<?php
// Only one route here, for our front end
// The API is handled by our qpay/api package
    Route::get('/', function() {

        return view('web::terminal');
    });