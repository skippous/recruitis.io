<?php declare(strict_types = 1);

namespace Tests\Controller;

use App\Repository\API\JobRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class JobControllerTest extends WebTestCase
{

	private JobRepository $jobRepository;

	protected function setUp(): void
	{
		parent::setUp();

		$this->jobRepository = $this->createMock(JobRepository::class);
	}

	public function testListAction(): void
	{
		// Mocking the JobRepository response
		$jobsData = [
			'jobs_total_count' => 10,
			'jobs' => [
				[
					'title' => 'Test Job 1',
					'dateCreated' => new DateTime('2023-06-30 09:28:00'),
					'salary' => [
						'min' => '1000',
						'max' => '2000',
						'currency' => 'CZK',
						'unit' => 'month',
					],
				],
				// Add more job data as needed for the test
			],
		];

		// @phpstan-ignore-next-line
		$this->jobRepository->method('fetchJobs')->willReturn($jobsData);

		// Create a client and request the route
		$client = static::createClient();
		$client->getContainer()->set(JobRepository::class, $this->jobRepository);

		$crawler = $client->request('GET', '/jobs/1');

		// Check if the response status code is 200
		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		// Check if the page contains the job title
		$this->assertSelectorTextContains('h5', 'Test Job 1');

		// Check if the pagination is rendered
		$this->assertCount(1, $crawler->filter('.pagination'));
	}

}
