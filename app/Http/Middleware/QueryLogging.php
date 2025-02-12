<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://www.elastic.co/licensing/elastic-license
 */

namespace App\Http\Middleware;

use App\DataMapper\Analytics\DbQuery;
use App\Utils\Ninja;
use Closure;
use DB;
use Illuminate\Http\Request;
use Turbo124\Beacon\Facades\LightLogs;

/**
 * Class QueryLogging.
 */
class QueryLogging
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        // Enable query logging for development
        if (!Ninja::isHosted() || !config('beacon.enabled')) {
            return $next($request);
        }

        $timeStart = microtime(true);
        DB::enableQueryLog();
        $response = $next($request);

        // hide requests made by debugbar
        if (strstr($request->url(), '_debugbar') === false) {
            $queries = DB::getQueryLog();
            $count = count($queries);
            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;
        
            if ($count > 150) {
                nlog($queries);
            }
            
            $ip = '';
            
            if (request()->header('Cf-Connecting-Ip')) {
                $ip = request()->header('Cf-Connecting-Ip');
            } else {
                $ip = request()->ip();
            }

            LightLogs::create(new DbQuery($request->method(), urldecode($request->url()), $count, $time, $ip))
                 ->batch();
        }
        

        return $response;
    }
}
