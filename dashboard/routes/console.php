<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure-based console
| commands. Each Closure is bound to a command instance, allowing a
| simple approach to interacting with each command's I/O methods.
|
*/

/**
 * Defines the 'inspire' Artisan command.
 *
 * This is a classic, built-in Laravel command that serves as a simple example
 * of how to create a console command. When a developer runs `php artisan inspire`
 * in their terminal, it will display a random inspirational quote.
 */
Artisan::command('inspire', function () {
    // Inside the command's closure, '$this' refers to the command instance itself.
    // The `comment()` method is used to output a message to the console,
    // typically styled in green for informational purposes.
    // `Inspiring::quote()` is a static method that fetches a random quote.
    $this->comment(Inspiring::quote());
})
// The `purpose()` method sets the description for the command.
// This description is visible when a user runs `php artisan list` or `php artisan inspire --help`.
->purpose('Display an inspiring quote');