<?php
/**
 * This file is part of the Bumz\ShortUrlBundle.
 *
 * (c) Artem Lopata <biozshock@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bumz\ShortUrlBundle\Tests\Extension;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use Bumz\ShortUrlBundle\Tests\Fixtures\ShortenController;

/**
 * Test case for Twig extension
 *
 * @author Artem Lopata <biozshock@gmail.com>
 */
class ShortenTwigExtensionTest extends WebTestCase
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
     * Test for checking twig extension
     */
    public function testTwigShortUrl()
    {
        $longUrl = 'http://example.com';

        $testController = new ShortenController();

        $testController->setContainer($this->client->getContainer());

        $result = $testController->testTemplate($longUrl);

        $shortUrl = $this->em->getRepository('BumzShortUrlBundle:ShortUrl')->findOneBy(array('long' => $longUrl));

        $this->assertEquals('/~' . $shortUrl->getShort(), $result->getContent());
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
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
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
