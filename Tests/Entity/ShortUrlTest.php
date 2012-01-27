<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\Entity;

use Bumz\ShortUrlBundle\Entity\ShortUrl;

/**
 * Test case for short url entity
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests short url model default setters and getters
     *
     * @param int   $method method to execute
     * @param mixed $value  value for setter
     *
     * @dataProvider methodsProvider
     */
    public function testSetterGetter($method, $value)
    {
        $shortUrl = new ShortUrl();

        $setter = 'set' . ucfirst($method);
        $getter = 'get' . ucfirst($method);
        $shortUrl->$setter($value);
        $this->assertEquals($value, $shortUrl->$getter());
    }

    /**
     * Test getter of Id
     */
    public function testGetId()
    {
        $shortUrl = new ShortUrl();

        $this->assertNull($shortUrl->getId());
    }

    /**
     * Tests for increase clicks with different start clicks and increments
     *
     * @param int $by     Increment count
     * @param inr $start  Start count of clicks
     * @param int $result Resulting count of clicks
     *
     * @dataProvider clicksProvider
     * @depends      testSetterGetter
     */
    public function testIncreaseClicks($by, $start, $result)
    {
        $shortUrl = new ShortUrl();

        $shortUrl->setClicks($start);
        $this->assertEquals($result, $shortUrl->increaseClicks($by));
    }

    /**
     * PHPUnit provider with methods of ShortUrl Entity
     *
     * @static
     *
     * @return array
     */
    public static function methodsProvider()
    {
        return array(
            array('Long', 'http://long.example.com'),
            array('Short', 'http://e.com/Sh'),
            array('Clicks', 9),
        );
    }

    /**
     * PHPUnit provider with clicks cases
     *
     * @static
     *
     * @return array
     */
    public static function clicksProvider()
    {
        return array(
            array(0, 1, 1),
            array(10, 3, 13),
            array(1000000, 1, 1000001),
            array(3021, 25, 3021 + 25), // :)
        );
    }
}
