<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 10:48 PM
 */

namespace EasySmartProgram\Auth;

use EasySmartProgram\Support\Http\Middleware\MiddlewareInterface;
use EasySmartProgram\Support\ServiceContainer;
use Psr\Http\Message\RequestInterface;

/**
 * Class AccessTokenMiddleware
 * @package EasySmartProgram\Auth
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class AccessTokenMiddleware implements MiddlewareInterface
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'smart-program-access-token';
    }

    /**
     * @return callable
     */
    public function callable(): callable
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if (isset($options['without_token']) && $options['without_token']) {
                    return $handler($request, $options);
                }

                $app = $options['app'] ?? null;
                if (!empty($app) && $app instanceof ServiceContainer && isset($app['access_token'])) {
                    parse_str($request->getUri()->getQuery(), $query);
                    $query = http_build_query(array_merge($app['access_token']->getQuery(), $query));
                    $request = $request->withUri($request->getUri()->withQuery($query));
                }

                return $handler($request, $options);
            };
        };
    }
}