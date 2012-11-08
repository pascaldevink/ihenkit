<?php

use Behat\Behat\Context\ClosuredContextInterface,
	Behat\Behat\Context\TranslatedContextInterface,
	Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Behat\Event\SuiteEvent;
use Behat\Behat\Event\ScenarioEvent;
use DoctrineExtensions\PHPUnit\Event\EntityManagerEventArgs;
use Behat\Gherkin\Node\PyStringNode,
	Behat\Gherkin\Node\TableNode;

require_once __DIR__.'/../../vendor/phpunit/phpunit/PHPUnit/Autoload.php';
require_once __DIR__.'/../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
	private $app;
	private $url;
	private $userId;
	private $output;

	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 *
	 * @param array $parameters context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters)
	{
		$this->app = require __DIR__ . '/../../config/app-config.php';
		$this->app['debug'] = true;
		$this->app['exception_handler']->disable();

		$em = \Tweakers\IHenkItTest\Lib\OrmConnection::createInMemory();
		$this->app['orm.em'] = $em;

		$eventManager = $em->getEventManager();
		if ($eventManager->hasListeners('preTestSetUp'))
		{
			$eventManager->dispatchEvent('preTestSetUp', new EntityManagerEventArgs($em));
		}
	}

//
// Place your definition and hook methods here:
//

	use ApiTraits;

	/**
	 *
	 * @Given /^I am at the url "([^"]*)"$/
	 */
	public function iAmAtTheUrl($url)
	{
		$this->url = $url;
	}

	/**
	 *
	 * @Given /^I am identified by the userId "([^"]*)"$/
	 */
	public function iAmIdentifiedByTheUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * @param Behat\Gherkin\Node\PyStringNode $string
	 *
	 * @throws Exception
	 * @return void
	 *
	 * @Then /^I should get:$/
	 */
	public function iShouldGet(PyStringNode $string)
	{
		assertEquals($this->output, $string);
	}
}
