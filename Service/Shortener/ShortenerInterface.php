<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Service\Shortener;

/**
 * Interface for shorteners
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
interface ShortenerInterface
{
    /**
     * Shorten URL method.
     *
     * @param string $longUrl long url to shorten
     *
     * @abstract
     * @return string short url
     */
    public function shorten($longUrl);

    /**
     * Sets short URL minimum length.
     *
     * @param int $length minimum length of short url
     *
     * @abstract
     */
    public function setShortLength($length);

    /**
     * Gets short URL minimum length.
     *
     * @abstract
     * @return int
     */
    public function getShortLength();

    /**
     * Increase short URL length
     *
     * @abstract
     * @return int new url length
     */
    public function increaseShortLength();
}
