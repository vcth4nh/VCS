<?php

namespace App\Providers;

use Illuminate\Http\UploadedFile;
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
            return !User::query()
                ->where('uid', '!=', $uid)
                ->where('username', $value)
                ->exists();
        }, __('validation.not_exist_with_another_uid'));

        Validator::extend('phone_number', function ($attribute, $value) {
            return preg_match('/^\d{5,15}$/', $value);
        }, __('validation.phone_number'));

        Validator::extend('is_student', function ($attribute, $value) {
            return User::query()
                ->where('uid', $value)
                ->where('role', STUDENT)
                ->exists();
        }, __('validation.is_student'));

        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            if (!$value instanceof UploadedFile) {
                return false;
            }

            $extensions = implode(',', $parameters);
            $validator->addReplacer('file_extension', function (
                $message,
                $attribute,
                $rule,
                $parameters
            ) use ($extensions) {
                return \str_replace(':values', $extensions, $message);
            });

            $extension = strtolower($value->getClientOriginalExtension());

            return $extension !== '' && in_array($extension, $parameters);
        });
    }
}
