<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/12
 * Time: 8:33 PM
 */

namespace EasySmartProgram\Support\Http;

use EasySmartProgram\Support\Exception\RuntimeException;

/**
 * Class Response
 * @package EasySmartProgram\Support\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Response extends \GuzzleHttp\Psr7\Response implements \ArrayAccess
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Response constructor.
     * @param int    $status
     * @param array  $headers
     * @param null   $body
     * @param string $version
     * @param null   $reason
     */
    public function __construct(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        parent::__construct($status, $headers, $body, $version, $reason);

        $this->formatResponse();
    }

    /**
     * json to array
     */
    protected function formatResponse()
    {
        if (false !== strpos($this->getHeaderLine('Content-Type'), 'application/json')) {
            $this->data = \json_decode($this->getBody(), true);
        }
    }

    /**
     * @return array
     */
    public function array()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws RuntimeException
     */
    public function offsetSet($offset, $value)
    {
        throw new RuntimeException('Unavailable!');
    }

    /**
     * @param mixed $offset
     * @throws RuntimeException
     */
    public function offsetUnset($offset)
    {
        throw new RuntimeException('Unavailable!');
    }


}