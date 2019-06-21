<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/21
 * Time: 2:53 PM
 */

namespace EasySmartProgram\Support\Http;

use EasySmartProgram\Support\Collection\Collection;
use GuzzleHttp\Psr7\Response;

/**
 * Class ResponseCast
 * @package EasySmartProgram\Support\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ResponseHandler
{
    const TYPE_ARRAY = 'array';
    const TYPE_COLLECTION = 'collection';
    const TYPE_GUZZLE_RESPONSE = 'guzzle';

    /**
     * @param Response $response
     * @param string   $type
     * @return mixed
     */
    public function castResponse(Response $response, $type = self::TYPE_COLLECTION)
    {
        if ($type == self::TYPE_ARRAY) {
            return $this->castResponseToArray($response);
        }

        if ($type == self::TYPE_COLLECTION) {
            $items = $this->castResponseToArray($response);
            return is_array($items) ? new Collection($items) : $items;
        }

        return $response;
    }

    /**
     * @param Response $response
     * @return array|mixed
     */
    protected function castResponseToArray(Response $response)
    {
        if (false !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            return json_decode($response->getBody(), true);
        }

        return $response->getBody();
    }
}