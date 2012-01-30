<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use Bumz\ShortUrlBundle\Entity\ShortUrl;

/**
 * Test case for controller
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortControllerTest extends WebTestCase
{
    protected $client;
    protected $em;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getEntityManager();

        $this->generateSchema();

        parent::setUp();
    }

    /**
     * @expects \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testUnknownUrlWillBeNotFound()
    {
        $this->client->request('GET', '/~nonexistent');
    }

    /**
     * Test case for checking clicks counts
     */
    public function testClick()
    {
        $long = 'http://example.com';
        $short = 'Og';

        $shortObject = $this->em->getRepository('BumzShortUrlBundle:ShortUrl')->findOneBy(array('short' => $short));
        $this->assertNull($shortObject);

        $shortObject = new ShortUrl();
        $shortObject->setClicks(0);
        $shortObject->setLong($long);
        $shortObject->setShort($short);
        $this->em->persist($shortObject);
        $this->em->flush();

        $this->client->followRedirects(false);
        $this->client->request('GET', '/~' . $short);

        $this->assertTrue($this->client->getResponse()->isRedirect($long));

        $shortObject = $this->em->getRepository('BumzShortUrlBundle:ShortUrl')->findOneBy(array('short' => $short));
        $this->assertEquals(1, $shortObject->getClicks());
    }

    /**
     * Generates schema if needed
     *
     * @throws Doctrine\DBAL\Schema\SchemaException
     */
    protected function generateSchema()
    {
        // Get the metadatas of the application to create the schema.
        $metadatas = $this->getMetadatas();

        if ( ! empty($metadatas)) {
            // Create SchemaTool
            $tool = new SchemaTool($this->em);
            $tool->createSchema($metadatas);
        } else {
            throw new \Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadatas.
     *
     * @return Array
     */
    protected function getMetadatas()
    {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }
}
