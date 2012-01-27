<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\Service\Shortener;

use Bumz\ShortUrlBundle\Service\Shortener\BaseShortener;

/**
 * Test case for testing base shortener
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class BaseShortenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests short url will be exact length as given
     *
     * @param int $length length of short url
     *
     * @dataProvider lengthProvider
     */
    public function testShortenLength($length)
    {
        $longUrl = 'http://example.com/url';
        $shortener = new BaseShortener();
        $shortener->setShortLength($length);

        $this->assertEquals($length, $shortener->getShortLength());
        $this->assertEquals($shortener->getShortLength(), strlen($shortener->shorten($longUrl)));
    }

    /**
     * Tests short url will be exact length as given
     *
     * @param int $length length of short url
     *
     * @dataProvider lengthProvider
     */
    public function testShortenWillReturnSameUrl($length)
    {
        $longUrl = 'http://example.com/url';
        $shortener = new BaseShortener();
        $shortener->setShortLength($length);

        $first = $shortener->shorten($longUrl);

        $this->assertEquals($first, $shortener->shorten($longUrl));
    }

    /**
     * Tests short url will increase length of url
     *
     * @param int $length length of short url
     *
     * @dataProvider lengthProvider
     */
    public function testIncreaseShortenLength($length)
    {
        $longUrl = 'http://example.com/url';
        $shortener = new BaseShortener();
        $shortener->setShortLength($length);

        $first = $shortener->shorten($longUrl);

        $shortener->increaseShortLength();

        $this->assertNotEquals($first, $shortener->shorten($longUrl));
        $this->assertEquals(strlen($first), strlen($shortener->shorten($longUrl)) - 1);
    }

    /**
     * Data provider of url lengths
     *
     * @static
     * @return array
     */
    public static function lengthProvider()
    {
        return array(
            array(3),
            array(8),
            array(9),
            array(12),
            array(120),
        );
    }
}
