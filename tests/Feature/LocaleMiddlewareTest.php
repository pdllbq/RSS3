<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LocaleMiddlewareTest extends TestCase
{
    public function test_it_sets_locale_from_first_url_segment(): void
    {
        Route::get('/ru', function () {
            return app()->getLocale();
        });

        $response = $this->get('/ru');

        $response->assertOk();
        $response->assertSee('ru');
    }

    public function test_it_falls_back_to_default_locale_when_prefix_is_not_supported(): void
    {
        Route::get('/about', function () {
            return app()->getLocale();
        });

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee(config('app.locale'));
    }
}
