<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/13
 * Time: 3:47 PM
 */

namespace EasySmartProgram\Support\Test;

use Mockery;

/**
 * Class TestCase
 * @package EasySmartProgram\Support\Test
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Tear down the test case.
     */
    public function tearDown(): void
    {
        if (class_exists('Mockery')) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }
    }
}