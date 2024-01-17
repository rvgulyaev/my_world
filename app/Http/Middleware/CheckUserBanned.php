<?php
namespace App\Http\Middleware;
use Closure;

class CheckUserBanned {

    public function handle($request, Closure $next) {
        if (auth()->check() && auth()->user()->banned_at && now()->greaterThan(auth()->user()->banned_at)) {
            $message = 'Ваш аккаунт заблокирован. Обратитесь к Администратору системы.';
            auth()->logout();
            return to_route('login')->with(['message' => $message]);
        }
        return $next($request);
    }
}