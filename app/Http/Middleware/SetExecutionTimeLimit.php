<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetExecutionTimeLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('production')) {
            set_time_limit(700);  // Augmenter le timeout à 5 minutes en production
        }

        return $next($request);
    }
}
