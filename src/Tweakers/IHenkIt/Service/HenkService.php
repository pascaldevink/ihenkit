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
		$dql = "SELECT h FROM Tweakers\IHenkIt\Entity\Henk h ORDER BY h.created DESC";

		$query = $this->entityManager->createQuery($dql);
		$query->setMaxResults(500);

		/**
		 * @var $henks \Tweakers\IHenkIt\Entity\Henk[]
		 */
		$henks = $query->getResult();

		$groupedHenks = array();
		foreach($henks as $henk)
		{
			$identifier = $henk->getContentType().'-'.$henk->getContentId();
			if (!isset($groupedHenks[$identifier]))
			{
				$groupedHenks[$identifier] = array(
					'contentType' => $henk->getContentType(),
					'contentId' => $henk->getContentId(),
					'henks' => 0);
			}

			$groupedHenks[$identifier]['henks']++;
		}

		usort($groupedHenks, array($this, 'orderByHenks'));

		return $groupedHenks;
	}

	public function orderByHenks($content1, $content2)
	{
		if ($content1['henks'] > $content2['henks'])
			return -1;
		else if ($content1['henks'] < $content2['henks'])
			return 1;

		return 0;
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

		$nrOfHenks = current($henks)['henks'];

		return (int)$nrOfHenks;
	}

	/**
	 * @param $contentType
	 * @param $contentId
	 * @param $userId
	 *
	 * @return bool
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function addHenk($contentType, $contentId, $userId)
	{
		$henk = new \Tweakers\IHenkIt\Entity\Henk();
		$henk->setContentType($contentType);
		$henk->setContentId($contentId);
		$henk->setUserId($userId);
		$henk->setCreated(new \DateTime());

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
