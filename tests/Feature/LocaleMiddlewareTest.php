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

    public function test_it_sets_latvian_locale_from_first_url_segment(): void
    {
        Route::get('/lv', function () {
            return app()->getLocale();
        });

        $response = $this->get('/lv');

        $response->assertOk();
        $response->assertSee('lv');
    }

    public function test_it_does_not_set_english_locale_while_unsupported(): void
    {
        Route::get('/en', function () {
            return app()->getLocale();
        });

        $response = $this->get('/en');

        $response->assertOk();
        $response->assertSee(config('app.locale'));
        $response->assertDontSee('en', false);
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
