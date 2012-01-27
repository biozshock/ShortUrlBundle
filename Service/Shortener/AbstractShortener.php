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
 * Abstract for shorteners
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
abstract class AbstractShortener implements ShortenerInterface
{
    private $urlLength = 3;

    /**
     * Gets short URL minimum length.
     *
     * @return int
     */
    public function getShortLength()
    {
        return $this->urlLength;
    }

    /**
     * Sets short URL minimum length.
     *
     * @param int $length minimum length of short url
     */
    public function setShortLength($length)
    {
        $this->urlLength = $length;
    }

    /**
     * Increase short URL length
     *
     * @return int
     */
    public function increaseShortLength()
    {
        return ++$this->urlLength;
    }


}
