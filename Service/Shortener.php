<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Service;

use Doctrine\ORM\EntityManager;
use Bumz\ShortUrlBundle\Entity\ShortUrl;

/**
 * Main shortener class
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class Shortener
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Bumz\ShortUrlBundle\Service\Shortener\ShortenerInterface
     */
    private $shortener;

    /**
     * @var \Bumz\ShortUrlBundle\Entity\ShortUrl
     */
    private $lastUrl;

    /**
     * DI constructor
     *
     * @param Shortener\ShortenerInterface $shortener Shortener instance
     * @param \Doctrine\ORM\EntityManager  $em        Entity manager
     */
    public function __construct(Shortener\ShortenerInterface $shortener, EntityManager $em)
    {
        $this->shortener = $shortener;
        $this->em = $em;
    }

    /**
     * Returns short url.
     *
     * If extension can't find appropriate short url it increases length of short
     *
     * @param string $url
     *
     * @return string
     */
    public function shorten($url)
    {
        $repository = $this->em->getRepository('BumzShortUrlBundle:ShortUrl');

        while (true) {
            $shortUrl = $this->shortener->shorten($url);

            if (null == $repository->findOneBy(array('short' => $shortUrl))) {
                break;
            }

            $this->shortener->increaseShortLength();
        };

        $this->saveUrl($shortUrl, $url);

        return '/~' . $shortUrl;
    }

    /**
     * Returns long url for short equivalent
     *
     * @param string $shortUrl
     *
     * @return null|string
     */
    public function getLong($shortUrl)
    {
        $this->lastUrl = $this->em->getRepository('BumzShortUrlBundle:ShortUrl')->findOneBy(array('short' => $shortUrl));

        return $this->lastUrl ? $this->lastUrl->getLong() : null;
    }

    /**
     * Sets click on long url and returns new value
     *
     * @return int
     */
    public function setClick()
    {
        if ($this->lastUrl) {
            $this->lastUrl->increaseClicks();
            $this->em->persist($this->lastUrl);
            $this->em->flush();
        }

        return $this->lastUrl->getClicks();
    }

    /**
     * Saves new short url to DB
     *
     * @param string $shortUrl Short url
     * @param string $longUrl  Long url
     */
    private function saveUrl($shortUrl, $longUrl)
    {
        $urlObject = new ShortUrl();
        $urlObject->setLong($longUrl);
        $urlObject->setShort($shortUrl);

        $this->em->persist($urlObject);
        $this->em->flush();
    }
}
