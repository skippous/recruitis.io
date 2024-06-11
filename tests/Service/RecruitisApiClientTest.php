<?php declare(strict_types = 1);

namespace Tests\Service;

use App\Service\RecruitisApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RecruitisApiClientTest extends TestCase
{

	public function testFetchJobs(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$logger = $this->createMock(LoggerInterface::class);
		$response = $this->createMock(ResponseInterface::class);

		$mockApiResponseContent = file_get_contents(__DIR__ . '/../mock_api_response.json');
		if ($mockApiResponseContent === false) {
			$this->fail('Failed to read mock API response.');
		}

		$mockApiResponse = json_decode($mockApiResponseContent, true);
		if ($mockApiResponse === null) {
			$this->fail('Failed to decode mock API response.');
		}

		$response->method('toArray')
			->willReturn($mockApiResponse);

		$httpClient->method('request')
			->willReturn($response);

		$client = new RecruitisApiClient($httpClient, $logger, 'YOUR_API_TOKEN', 'https://api.example.com');

		$jobsData = $client->fetchJobs(1);

		$this->assertIsArray($jobsData);
		$this->assertArrayHasKey('payload', $jobsData);
		$this->assertArrayHasKey('meta', $jobsData);
		$this->assertCount(1, $jobsData['payload']);
		$this->assertSame(431912, $jobsData['payload'][0]['job_id']);
		// Add more assertions as needed to verify all fields
	}

}
