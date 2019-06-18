<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 9:18 AM
 */

namespace EasySmartProgram\Tests\Support;

use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\ServiceContainer;
use Mockery;
use EasySmartProgram\Tests\TestCase;

/**
 * Class ComponentTest
 * @package EasySmartProgram\Tests\Support
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ComponentTest extends TestCase
{
    /**
     * test get application
     */
    public function testGetApplication()
    {
        $application = Mockery::mock(ServiceContainer::class);
        $component = new Component($application);
        $this->assertInstanceOf(ServiceContainer::class, $component->app());
    }
}