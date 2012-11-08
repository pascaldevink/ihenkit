<?php

trait ApiTraits
{
	/**
	 *
	 * @When /^I request the number of henks$/
	 */
	public function iRequestTheNumberOfHenks()
	{
		$client = new \Symfony\Component\HttpKernel\Client($this->app);
		$crawler = $client->request(
			'GET',
			'/list',
			array(
				'userId'     => $this->userId,
				'url'        => $this->url
			));

		$this->output = $client->getResponse()->getContent();
	}

	/**
	 * @When /^I henk the current url$/
	 */
	public function iHenkTheCurrentUrl()
	{
		$client = new \Symfony\Component\HttpKernel\Client($this->app);
		$crawler = $client->request(
			'POST',
			'/henk',
			array(
				'userId'	=> $this->userId,
				'url'		=> $this->url
			));

		$this->output = $client->getResponse()->getContent();
	}
}