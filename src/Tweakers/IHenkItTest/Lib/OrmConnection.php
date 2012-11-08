<?php

namespace Tweakers\IHenkItTest\Lib;

use Doctrine\Common\EventManager;

class OrmConnection
{

	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public static function createInMemory()
	{
		$configuration = new \Doctrine\ORM\Configuration();
		$driver = $configuration->newDefaultAnnotationDriver(array(__DIR__."/../../IHenkIt/Entity"), true);
		$configuration->setMetadataDriverImpl($driver);
		$configuration->setProxyDir('../../cache/proxies');
		$configuration->setProxyNamespace("Tweakers\IHenkIt\Entity");

		$eventManager = new EventManager();
		$eventManager->addEventListener(array("preTestSetUp"), new SchemaSetupListener());

		return \Doctrine\ORM\EntityManager::create(
			array(
				'driver'	=> 'pdo_sqlite',
				'memory'	=> true
			),
			$configuration,
			$eventManager
		);
	}
}
