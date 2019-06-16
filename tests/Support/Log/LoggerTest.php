<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/14
 * Time: 9:32 AM
 */

namespace EasySmartProgram\Tests\Support\Log;

use Mockery;
use Monolog\Logger as MonoLogger;
use EasySmartProgram\Tests\TestCase;
use EasySmartProgram\Support\Log\Logger;
use EasySmartProgram\Support\Log\Driver\Factory;

/**
 * Class LoggerTest
 * @package EasySmartProgram\Tests\Support\Log
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class LoggerTest extends TestCase
{
    /**
     * test logger interface
     */
    public function testLoggerInterface()
    {
        $config = [
            'default' => 'single',
            'channels' => [
                'single' => [
                    'driver' => 'single',
                ],
            ]
        ];

        $monoLogger = Mockery::mock(MonoLogger::class);
        $monoLogger->shouldReceive('emergency')->with('emergency message', []);
        $monoLogger->shouldReceive('alert')->with('alert message', []);
        $monoLogger->shouldReceive('critical')->with('critical message', []);
        $monoLogger->shouldReceive('error')->with('error message', []);
        $monoLogger->shouldReceive('warning')->with('warning message', []);
        $monoLogger->shouldReceive('notice')->with('notice message', []);
        $monoLogger->shouldReceive('info')->with('info message', []);
        $monoLogger->shouldReceive('debug')->with('debug message', []);
        $monoLogger->shouldReceive('log')->with('debug', 'log message', []);

        $factory = Mockery::mock(Factory::class);
        $factory->shouldReceive('make')->andReturn($monoLogger);
        $logger = new Logger($factory, $config);

        $logger->emergency('emergency message');
        $logger->alert('alert message');
        $logger->critical('critical message');
        $logger->error('error message');
        $logger->warning('warning message');
        $logger->notice('notice message');
        $logger->info('info message');
        $logger->debug('debug message');
        $logger->log('debug', 'log message');
    }

    /**
     * test change channel
     */
    public function testChangeChannel()
    {
        $config = [
            'default' => 'single',
            'channels' => [
                'single' => [
                    'driver' => 'single',
                ],
                'daily' => [
                    'driver' => 'daily',
                ]
            ]
        ];

        $singleDriverLogger = Mockery::mock(MonoLogger::class);
        $dailyDriverLogger = Mockery::mock(MonoLogger::class);

        $factory = Mockery::mock(Factory::class);
        $factory->shouldReceive('make')->with('single', ['driver' => 'single'])->andReturn($singleDriverLogger);
        $factory->shouldReceive('make')->with('daily', ['driver' => 'daily'])->andReturn($dailyDriverLogger);

        $logger = new Logger($factory, $config);

        $this->assertSame($singleDriverLogger, $logger->channel('single')->driver());
        $this->assertSame($dailyDriverLogger, $logger->channel('daily')->driver());
    }

    /**
     * test stack channel
     */
    public function testStackChannel()
    {
        $config = [
            'default' => 'stack',
            'channels' => [
                'single' => [
                    'driver' => 'single',
                ],
                'daily' => [
                    'driver' => 'daily',
                ],
                'stack' => [
                    'driver' => 'stack',
                    'channels' => ['single', 'daily'],
                ],
            ],
        ];

        $receiveConfig = array_merge($config['channels']['stack'], ['total_channels' => $config['channels']]);

        $factory = Mockery::mock(Factory::class);
        $factory->shouldReceive('make')->with('stack', $receiveConfig);

        $logger = new Logger($factory, $config);
        $logger->stack(['single', 'daily']);
    }
}