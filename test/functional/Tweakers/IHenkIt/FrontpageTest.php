<?php

namespace Tweakers\IHenkIt;

use DoctrineExtensions\PHPUnit\Event\EntityManagerEventArgs;

class FrontpageTest extends \Silex\WebTestCase
{

	/**
	 * Creates the application.
	 *
	 * @return HttpKernel
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../../../../config/app-config.php';
		$app['debug'] = true;
		$app['exception_handler']->disable();

		$em = \Tweakers\IHenkItTest\Lib\OrmConnection::createInMemory();
		$app['orm.em'] = $em;

		$eventManager = $em->getEventManager();
		if ($eventManager->hasListeners('preTestSetUp')) {
			$eventManager->dispatchEvent('preTestSetUp', new EntityManagerEventArgs($em));
		}

		return $app;
	}

	public function testIndex()
	{
		$client = $this->createClient();
		$crawler = $client->request('GET', '/');

		$response = $client->getResponse();
		$this->assertTrue($response->isOk());
		$this->assertCount(1, $crawler->filter('h1:contains("Installatie")'));
		$this->assertCount(1, $crawler->filter('h1:contains("Statistieken")'));
		$this->assertCount(2, $crawler->filter('h5'));
		$this->assertEquals(1, $crawler->filter('.tab-content > .active')->count());
		$this->assertEquals('firefox', $crawler->filter('.tab-content > .active')->attr('id'));
	}
}
