<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Extensions;

use Bumz\ShortUrlBundle\Service\Shortener;

/**
 * Twig extension for shortening long urls inside twig templates
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortenTwigExtension extends \Twig_Extension
{
    /**
     * @var \Bumz\ShortUrlBundle\Service\Shortener
     */
    private $shortener;

    /**
     * DI constructor
     *
     * @param \Bumz\ShortUrlBundle\Service\Shortener $shortener
     */
    public function __construct(Shortener $shortener)
    {
        $this->shortener = $shortener;
    }

    /**
     * Provide list of twig helpers provided by this extension
     *
     * @return array array of twig helper functions
     */
    public function getFilters()
    {
        return array(
            'shortenUrl'  => new \Twig_Filter_Method($this, 'shortenUrl'),
        );
    }

    /**
     * Shorten url Twig extension
     *
     * @param string $url a url that need to be shortened
     *
     * @return string
     */
    public function shortenUrl($url)
    {
        return $this->shortener->shorten($url);
    }

    /**
     * Returns name of extension
     *
     * @return string name of extenstion
     */
    public function getName()
    {
        return 'shorten_url';
    }

}