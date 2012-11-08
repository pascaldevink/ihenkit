<?php

namespace Tweakers\IHenkItTest\Lib;

abstract class AbstractOrmTestCase extends \DoctrineExtensions\PHPUnit\OrmTestCase
{
	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	protected function createEntityManager()
	{
		return OrmConnection::createInMemory();
	}
}
