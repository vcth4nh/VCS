<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('not_exist_with_another_uid', function ($attribute, $value, $uid) {
            return !User::where('uid', '!=', $uid)->where('username', $value)->exists();
        }, __('validation.not_exist_with_another_uid'));

        Validator::extend('phone_number', function ($attribute, $value) {
            return preg_match('/^\d{5,15}$/', $value);
        }, __('validation.phone_number'));
        Validator::extend('is_student', function ($attribute, $value) {
            return User::where('uid', $value)->where('role', STUDENT)->exists();
        },__('validation.is_student'));
    }
}
