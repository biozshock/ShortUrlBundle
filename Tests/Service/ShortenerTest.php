<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Tests\Mocks\EntityManagerMock;
use Doctrine\ORM\Mapping\ClassMetadata;

use Bumz\ShortUrlBundle\Service\Shortener;
use Bumz\ShortUrlBundle\Service\Shortener\BaseShortener;

/**
 * Test case for shortener service
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests short url will be exact length as given
     */
    public function testShorten()
    {
        $longUrl = 'http://example.com/url';
        $shortUrl = 'TuO';

        $shortenerClass = $this->getMock('\Bumz\ShortUrlBundle\Service\Shortener\BaseShortener');

        $shortenerClass->expects($this->once())
                    ->method('shorten')
                    ->with($longUrl)
                    ->will($this->returnValue($shortUrl));

        $shortenerClass->expects($this->never())
            ->method('increaseShortLength');

        $entityManager = $this->getMock('\Doctrine\ORM\EntityManager', array(), array(), '', false);

        $metadataMock = $this->getMock(
            '\Doctrine\ORM\Mapping\ClassMetadata',
            array(),
            array('\Bumz\ShortUrlBundle\Entity\ShortUrl')
        );

        $repository = $this->getMock('\Doctrine\ORM\EntityRepository', array(), array($entityManager, $metadataMock));
        $repository->expects($this->once())
                ->method('findOneBy')
                ->with(array('short' => $shortUrl))
                ->will($this->returnValue(null));

        $entityManager->expects($this->once())
                    ->method('getRepository')
                    ->with('BumzShortUrlBundle:ShortUrl')
                    ->will($this->returnValue($repository));

        $shortener = new Shortener($shortenerClass, $entityManager);
        $shortResult = $shortener->shorten($longUrl);

        $this->assertEquals('/~' . $shortUrl, $shortResult);
    }

    /**
     * Tests short url will be exact length as given
     *
     * @return void
     */
    public function testShortenIncreaseLengthWhenAlreadyExists()
    {
        $longUrl = 'http://example.com/url';
        $shortUrl = 'TuO';

        $shortenerClass = $this->getMock('\Bumz\ShortUrlBundle\Service\Shortener\BaseShortener');

        $shortenerClass->expects($this->exactly(2))
            ->method('shorten')
            ->with($longUrl)
            ->will($this->returnCallback(function() use (&$shortUrl) {

                return $shortUrl;
            }));

        $shortenerClass->expects($this->once())
            ->method('increaseShortLength')
            ->will($this->returnValue(strlen($shortUrl) + 1));

        $entityManager = $this->getMock('\Doctrine\ORM\EntityManager', array(), array(), '', false);

        $metadataMock = $this->getMock(
            '\Doctrine\ORM\Mapping\ClassMetadata',
            array(),
            array('\Bumz\ShortUrlBundle\Entity\ShortUrl')
        );

        $repository = $this->getMock('\Doctrine\ORM\EntityRepository', array(), array($entityManager, $metadataMock));
        $repository->expects($this->exactly(2))
            ->method('findOneBy')
            ->with(array('short' => &$shortUrl))
            ->will($this->returnCallback(function() use (&$shortUrl) {

                if (strlen($shortUrl) == 3) {
                    $shortUrl .= 'T';
                    return true;
                }

                return null;
            }));

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with('BumzShortUrlBundle:ShortUrl')
            ->will($this->returnValue($repository));

        $shortener = new Shortener($shortenerClass, $entityManager);
        $shortResult = $shortener->shorten($longUrl);

        $this->assertEquals('/~' . $shortUrl, $shortResult);
    }

    /**
     * Test case for getting long url and setting a click for it
     */
    public function testGetLongAndSetClicks()
    {
        $longUrl = 'http://example.com/url';
        $shortUrl = 'TuO';

        $shortenerClass = $this->getMock('\Bumz\ShortUrlBundle\Service\Shortener\BaseShortener');

        $entityManager = $this->getMock('\Doctrine\ORM\EntityManager', array(), array(), '', false);

        $metadataMock = $this->getMock(
            '\Doctrine\ORM\Mapping\ClassMetadata',
            array(),
            array('\Bumz\ShortUrlBundle\Entity\ShortUrl')
        );

        $shortModelMock = $this->getMock('\Bumz\ShortUrlBundle\Entity\ShortUrl');
        $shortModelMock->expects($this->once())
            ->method('getLong')
            ->will($this->returnValue($longUrl));

        $shortModelMock->expects($this->once())
            ->method('increaseClicks');

        $shortModelMock->expects($this->once())
            ->method('getClicks')
            ->will($this->returnValue(2));

        $repository = $this->getMock('\Doctrine\ORM\EntityRepository', array(), array($entityManager, $metadataMock));
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(array('short' => $shortUrl))
            ->will($this->returnValue($shortModelMock));

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with('BumzShortUrlBundle:ShortUrl')
            ->will($this->returnValue($repository));

        $entityManager->expects($this->once())
            ->method('persist');

        $entityManager->expects($this->once())
            ->method('flush');

        $shortener = new Shortener($shortenerClass, $entityManager);
        $longResult = $shortener->getLong($shortUrl);

        $this->assertEquals($longUrl, $longResult);
        $this->assertEquals(2, $shortener->setClick());
    }

}
