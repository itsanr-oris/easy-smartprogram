<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/16
 * Time: 2:35 PM
 */

namespace EasySmartProgram\Tests\Support\Http\Middleware;

use GuzzleHttp\MessageFormatter;
use function GuzzleHttp\Psr7\str;
use Psr\Log\LogLevel;
use Psr\Log\Test\TestLogger;
use EasySmartProgram\Support\Http\Middleware\LogMiddleware;
use EasySmartProgram\Support\Http\Middleware\MiddlewareInterface;
use EasySmartProgram\Support\Test\HttpClientMiddlewareTestCase;

/**
 * Class LoggerMiddlewareTest
 * @package EasySmartProgram\Tests\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class LogMiddlewareTest extends HttpClientMiddlewareTestCase
{
    /**
     * @var TestLogger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $formatter;

    /**
     * @var string
     */
    protected $level;

    /**
     * @return MiddlewareInterface
     */
    public function middleware(): MiddlewareInterface
    {
        $this->logger = new TestLogger();
        $this->formatter = MessageFormatter::DEBUG;
        $this->level = LogLevel::DEBUG;

        return new LogMiddleware($this->logger, ['log_template' => '{response}', 'log_level' => $this->level]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testLogMiddleware()
    {
        $response = $this->appendResponse()->client()->request('GET', '/');

        $this->assertCount(1, $this->logger->records);
        $this->assertSame($this->level, $this->logger->records[0]['level']);
        $this->assertSame(str($response), $this->logger->records[0]['message']);
    }
}