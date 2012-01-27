<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
Symfony\Component\DependencyInjection\Definition,
Symfony\Component\DependencyInjection\Reference;

use Bumz\ShortUrlBundle\DependencyInjection\BumzShortUrlExtension;

/**
 * Test case for DI extension
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class BumzShortUrlExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $extension;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new BumzShortUrlExtension();
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        unset($this->container, $this->extension);
    }

    /**
     * Test for checking for custom config loads
     */
    public function testLoadCustomConfig()
    {
        $shortenerClass = 'Bumz\\ShortUrlBundle\\Tests\\DependencyInjection\\TestShortener';
        $shortLength = 4;

        $shortenerCalls = array(
            array(
                'setShortLength',
                array(
                    $shortLength
                )
            )
        );

        $this->container->setDefinition(
            'bumz_short_url.shortener_test',
            new Definition($shortenerClass)
        );
        $config = array(
            'bumz_short_url' => array(
                'shortener'   => 'test',
                'shortLength' => $shortLength,
            ),
        );
        $this->extension->load($config, $this->container);

        $shortenerDefinition = $this->container->getDefinition('bumz_short_url.shortener')->getArgument(0);

        $this->assertEquals($shortenerClass, $shortenerDefinition->getClass());
        $this->assertEquals($shortenerCalls, $shortenerDefinition->getMethodCalls());
    }

    /**
     * Test for checking for default config loads
     */
    public function testLoadDefaultConfig()
    {
        $shortenerClass = '%bumz_short_url.shortener_base.class%';
        $shortLength = 3;

        $shortenerCalls = array(
            array(
                'setShortLength',
                array(
                    $shortLength
                )
            )
        );

        $this->extension->load(array(), $this->container);

        $shortenerDefinition = $this->container->getDefinition('bumz_short_url.shortener')->getArgument(0);

        $this->assertEquals($shortenerClass, $shortenerDefinition->getClass());
        $this->assertEquals($shortenerCalls, $shortenerDefinition->getMethodCalls());
    }
}
