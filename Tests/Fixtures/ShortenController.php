<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\Fixtures;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Fixture controller
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortenController extends Controller
{
    /**
     * Case for testing twig extension
     *
     * @param string $longUrl long url to shorten
     *
     * @return mixed
     */
    public function testTemplate($longUrl)
    {
        return $this->container->get('templating')->renderResponse(
            'BumzShortUrlBundle::testTemplate.html.twig',
            array(
                'longUrl' => $longUrl
            ));
    }
}
