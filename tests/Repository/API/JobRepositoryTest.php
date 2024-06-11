<?php declare(strict_types = 1);

namespace Tests\Repository\API;

use App\Model\Job\Job;
use App\Repository\API\JobRepository;
use App\Service\JobMapper;
use App\Service\RecruitisApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobRepositoryTest extends TestCase
{

	private CacheInterface $cache;
	private JobRepository $jobRepository;

	protected function setUp(): void
	{
		$this->cache = $this->createMock(CacheInterface::class);
		$httpClient = $this->createMock(HttpClientInterface::class);
		$logger = $this->createMock(LoggerInterface::class);
		$jobMapper = new JobMapper();
		$apiClient = new RecruitisApiClient($httpClient, $logger, 'YOUR_API_TOKEN', 'https://api.example.com');

		$this->jobRepository = new JobRepository(
			$this->cache,
			$jobMapper,
			$apiClient
		);
	}

	public function testFetchJobs(): void
	{
		$mockApiResponseContent = file_get_contents(__DIR__ . '/../../mock_api_response.json');
		if ($mockApiResponseContent === false) {
			$this->fail('Failed to read mock API response.');
		}

		$mockApiResponse = json_decode($mockApiResponseContent, true);
		if ($mockApiResponse === null) {
			$this->fail('Failed to decode mock API response.');
		}

		$cacheItem = $this->createMock(ItemInterface::class);
		$cacheItem->method('expiresAfter')->willReturnSelf();

		// @phpstan-ignore-next-line
		$this->cache->method('get')->willReturnCallback(
			function ($key, $callback) use ($mockApiResponse) {
				return $mockApiResponse;
			}
		);

		$result = $this->jobRepository->fetchJobs(1, 10);

		$this->assertIsArray($result);
		$this->assertArrayHasKey('jobs_total_count', $result);
		$this->assertArrayHasKey('jobs', $result);
		$this->assertSame(62, $result['jobs_total_count']);
		$this->assertContainsOnlyInstancesOf(Job::class, $result['jobs']);
		$this->assertCount(1, $result['jobs']);
	}

}
