<?php

namespace Tweakers\IHenkIt\Service;

class HenkService
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function getListOfHenkedContent()
	{
		$dql = "
		SELECT
			count(h.contentType) as henks, h.contentType, h.contentId, h.url
		FROM
			Tweakers\IHenkIt\Entity\Henk h
		GROUP BY
			h.contentType, h.contentId
		ORDER BY
			henks DESC";

		$query = $this->entityManager->createQuery($dql);
		$query->setMaxResults(10);
		$henks = $query->getResult();

		return $henks;
	}

	public function getLastHenked()
	{
		$dql = "SELECT h FROM Tweakers\IHenkIt\Entity\Henk h ORDER BY h.created DESC";
		$query = $this->entityManager->createQuery($dql);
		$query->setMaxResults(10);
		$henks = $query->getResult();

		return $henks;
	}

	public function getHenksByUserId($userId)
	{
		$dql = "SELECT h FROM Tweakers\IHenkIt\Entity\Henk h WHERE h.userId = :userId ORDER BY h.created DESC";

		$query = $this->entityManager->createQuery($dql);
		$query->setParameter('userId', $userId);
		$query->setMaxResults(30);
		$henks = $query->getResult();

		return $henks;
	}

	public function getNumberOfHenksByContentTypeAndId($contentType, $contentId)
	{
		$dql = "SELECT count(h.contentType) AS henks FROM Tweakers\IHenkIt\Entity\Henk h ".
			"WHERE h.contentType = :contentType AND h.contentId = :contentId";

		$query = $this->entityManager->createQuery($dql);
		$query->setParameter('contentType', $contentType);
		$query->setParameter('contentId', $contentId);
		$henks = $query->getScalarResult();

		$currentHenk = current($henks);
		$nrOfHenks = $currentHenk['henks'];

		return (int)$nrOfHenks;
	}

	/**
	 * @param $contentType
	 * @param $contentId
	 * @param $userId
	 * @param $url
	 *
	 * @throws \Doctrine\DBAL\DBALException
	 * @return bool
	 */
	public function addHenk($contentType, $contentId, $userId, $url)
	{
		$henk = new \Tweakers\IHenkIt\Entity\Henk();
		$henk->setContentType($contentType);
		$henk->setContentId($contentId);
		$henk->setUserId($userId);
		$henk->setCreated(new \DateTime());
		$henk->setUrl($url);

		try
		{
			$this->entityManager->persist($henk);
			$this->entityManager->flush();
			return true;
		}
		catch (\Doctrine\DBAL\DBALException $dbe)
		{
			if (strpos($dbe->getMessage(), '1062 Duplicate entry') !== false)
				return false;

			throw $dbe;
		}
	}
}
