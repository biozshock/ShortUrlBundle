<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Short controller.
 *
 * Controller accepts all short url and redirects to long equivalents
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortController extends Controller
{
    /**
     * Redirects user to long equivalent
     *
     * @param string $url A short url to be redirected to
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function shortAction($url)
    {
        $longUrl = $this->get('bumz_short_url.shortener')->getLong($url);

        if (!$longUrl) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('No short url found ' . $url);
        }

        $this->get('bumz_short_url.shortener')->setClick();

        return $this->redirect($longUrl);
    }

}
