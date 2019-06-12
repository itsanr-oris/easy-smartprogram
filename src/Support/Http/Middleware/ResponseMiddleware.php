<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 8:34 PM
 */

namespace EasySmartProgram\Support\Http\Middleware;

use EasySmartProgram\Support\Http\Response;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseMiddleware
 * @package EasySmartProgram\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ResponseMiddleware implements MiddlewareInterface
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'extend-response';
    }

    /**
     * @return callable
     */
    public function callable(): callable
    {
        return Middleware::mapResponse(function (ResponseInterface $response) {
            return new Response(
                $response->getStatusCode(),
                $response->getHeaders(),
                $response->getBody(),
                $response->getProtocolVersion(),
                $response->getReasonPhrase()
            );
        });
    }

}