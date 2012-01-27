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
 * Shortener that returns short url based on Base64
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class BaseShortener extends AbstractShortener
{
    /**
     * Shorten URL method.
     *
     * @param string $longUrl long url to shorten
     *
     * @return string short url
     */
    public function shorten($longUrl)
    {
        $url = md5($longUrl);
        $urlHash = '';

        do {
            $hash = $urlHash .= $url;
            $hash = pack('H*', $hash);

            $hash = base64_encode($hash);
            $hash = str_replace(array('+', '/', '='), array('', '', ''), $hash);

        } while (strlen($hash) < $this->getShortLength());

        return substr($hash, 0, $this->getShortLength());
    }

}
