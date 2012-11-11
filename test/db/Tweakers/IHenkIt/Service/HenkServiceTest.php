<?php

namespace Tweakers\IHenkIt\Service;

class HenkServiceTest extends \Tweakers\IHenkItTest\Lib\AbstractOrmTestCase
{
	/**
	 * Returns the test dataset.
	 *
	 * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet()
	{
		return $this->createFlatXMLDataSet(__DIR__."/../../../000_default_dataset.xml");
	}

	public function testAddHenk()
	{
		$henkService = new HenkService($this->getEntityManager());
		$returnValue = $henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');

		$this->assertTrue($returnValue);
	}

	public function testGetListOfHenkedContent()
	{
		$henkService = new HenkService($this->getEntityManager());
		$henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('News', 73418, 266226, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Reviews', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Video', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Download', 29543, 266225, 'http://tweakers.net/meuktracker/29543/adium-154.html');
		$henkService->addHenk('Download', 29543, 266226, 'http://tweakers.net/meuktracker/29543/adium-154.html');
		$henkService->addHenk('Download', 29543, 266227, 'http://tweakers.net/meuktracker/29543/adium-154.html');

		$henkedContent = $henkService->getListOfHenkedContent();

		$this->assertCount(4, $henkedContent);

		$this->assertEquals(3, $henkedContent[0]['henks']);
		$this->assertEquals('Download', $henkedContent[0]['contentType']);
		$this->assertEquals('http://tweakers.net/meuktracker/29543/adium-154.html', $henkedContent[0]['url']);

		$this->assertEquals(2, $henkedContent[1]['henks']);
		$this->assertEquals('News', $henkedContent[1]['contentType']);

		$this->assertEquals(1, $henkedContent[2]['henks']);
		$this->assertEquals('Reviews', $henkedContent[2]['contentType']);

		$this->assertEquals(1, $henkedContent[3]['henks']);
		$this->assertEquals('Video', $henkedContent[3]['contentType']);
	}

	public function testGetLastHenked()
	{
		$henkService = new HenkService($this->getEntityManager());
		$henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Download', 29543, 266226, 'http://tweakers.net/meuktracker/29543/adium-154.html');
		$henkService->addHenk('News', 73418, 266226, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Reviews', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');

		$lastHenked = $henkService->getLastHenked();

		$this->assertCount(4, $lastHenked);
		$this->assertInstanceOf('\Tweakers\IHenkIt\Entity\Henk', $lastHenked[0]);
		$this->assertInstanceOf('\Tweakers\IHenkIt\Entity\Henk', $lastHenked[1]);
		$this->assertInstanceOf('\Tweakers\IHenkIt\Entity\Henk', $lastHenked[2]);
		$this->assertInstanceOf('\Tweakers\IHenkIt\Entity\Henk', $lastHenked[3]);

		$this->assertEquals('News', $lastHenked[0]->getContentType());
		$this->assertEquals('Download', $lastHenked[1]->getContentType());
		$this->assertEquals('News', $lastHenked[2]->getContentType());
		$this->assertEquals('Reviews', $lastHenked[3]->getContentType());
	}

	public function testGetNumberOfHenksByContentTypeAndId()
	{
		$henkService = new HenkService($this->getEntityManager());
		$henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('News', 73418, 266226, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Download', 29543, 266225, 'http://tweakers.net/meuktracker/29543/adium-154.html');

		$henks = $henkService->getNumberOfHenksByContentTypeAndId('News', 73418);
		$this->assertEquals(2, $henks);

		$henks = $henkService->getNumberOfHenksByContentTypeAndId('Download', 29543);
		$this->assertEquals(1, $henks);

		$henks = $henkService->getNumberOfHenksByContentTypeAndId('Reviews', 3422);
		$this->assertEquals(0, $henks);
	}

	public function testHasHenked()
	{
		$henkService = new HenkService($this->getEntityManager());
		$henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('News', 73418, 266226, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Download', 29543, 266225, 'http://tweakers.net/meuktracker/29543/adium-154.html');

		$hasHenked = $henkService->hasHenked(266225, 'News', 73418);
		$this->assertTrue($hasHenked);

		$hasHenked = $henkService->hasHenked(266225, 'Download', 33874);
		$this->assertFalse($hasHenked);
	}

	public function testGetTotalNumberOfHenks()
	{
		$henkService = new HenkService($this->getEntityManager());

		$henks = $henkService->getTotalNumberOfHenks();
		$this->assertEquals(0, $henks);

		$henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('News', 73418, 266226, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('Download', 29543, 266225, 'http://tweakers.net/meuktracker/29543/adium-154.html');

		$henks = $henkService->getTotalNumberOfHenks();
		$this->assertEquals(3, $henks);
	}

	public function testGetTotalNumberOfHenkedContent()
	{
		$henkService = new HenkService($this->getEntityManager());

		$henkedContent = $henkService->getTotalNumberOfHenkedContent();
		$this->assertEquals(0, $henkedContent);

		// 1.
		$henkService->addHenk('News', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');
		$henkService->addHenk('News', 73418, 266226, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');

		// 2.
		$henkService->addHenk('Download', 29543, 266225, 'http://tweakers.net/meuktracker/29543/adium-154.html');
		$henkService->addHenk('Download', 29543, 266226, 'http://tweakers.net/meuktracker/29543/adium-154.html');

		// 3.
		$henkService->addHenk('Reviews', 73418, 266225, 'http://tweakers.net/nieuws/73418/hp-en-intel-blijven-itanium-ondersteunen.html');

		// 4.
		$henkService->addHenk('Reviews', 2819, 266225, 'http://tweakers.net/reviews/2819/apple-macbook-pro-13-inch-retina-ultraportable-met-puik-scherm.html');

		// 5.
		$henkService->addHenk('News', 2819, 266225, 'http://tweakers.net/nieuws/2819/star-wars-episode-1-racer-en-phantom-menace-reviews.html');

		$henkedContent = $henkService->getTotalNumberOfHenkedContent();
		$this->assertEquals(5, $henkedContent);
	}
}
