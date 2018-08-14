<?php

namespace OpnUC\AuthSAML2;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class AuthSAML2ServiceProvider extends \App\Providers\PbxLinkerServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        parent::register();

        // SAMLで認証できた場合
        Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function ($event) {
            // SAMLのユーザ情報を取得
            $user = $event->getSaml2User();

            // ユーザテーブルからユーザを検索
            $laravelUser = User::where('email', $user->getUserId())
                ->first();

            // ログインしたことにする
            Auth::login($laravelUser);
        });

        // SAMLでログアウトした場合
        Event::listen('Aacotroneo\Saml2\Events\Saml2LogoutEvent', function ($event) {
            //Auth::logout();
            //Session::save();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [AuthSAML2ServiceProvider::class];
    }
}