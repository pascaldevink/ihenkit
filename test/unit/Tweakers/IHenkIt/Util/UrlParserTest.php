<?php

namespace Tweakers\IHenkIt\Util;

class UrlParserTest extends \PHPUnit_Framework_TestCase
{
	public function testParseUrlPathForNews()
	{
		$urlParser = new UrlParser();
		$givenPath = '/nieuws/36753/test-artikel.html';

		$output = $urlParser->parseUrlPath($givenPath);
		$this->assertNotNull($output);
		$this->assertInstanceOf('\Tweakers\IHenkIt\Entity\ParsedPath', $output);
		$this->assertEquals('News', $output->getContentType());
		$this->assertEquals(36753, $output->getContentId());
	}

	public function testParseUrlPathForReviews()
	{
		$urlParser = new UrlParser();
		$givenPath = '/reviews/5664/test-review.html';

		$output = $urlParser->parseUrlPath($givenPath);
		$this->assertNotNull($output);
		$this->assertInstanceOf('\Tweakers\IHenkIt\Entity\ParsedPath', $output);
		$this->assertEquals('Reviews', $output->getContentType());
		$this->assertEquals(5664, $output->getContentId());
	}
}
