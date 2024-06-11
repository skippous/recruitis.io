<?php declare(strict_types = 1);

namespace App\Repository\API;

use App\Exception\ApiException;
use App\Model\Job\Job;
use App\Service\JobMapper;
use App\Service\RecruitisApiClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class JobRepository
{

	private const CACHE_EXPIRATION = 3600;

	private CacheInterface $cache;
	private JobMapper $mapper;
	private RecruitisApiClient $apiClient;

	public function __construct(CacheInterface $cache, JobMapper $mapper, RecruitisApiClient $apiClient)
	{
		$this->cache = $cache;
		$this->mapper = $mapper;
		$this->apiClient = $apiClient;
	}

	/** @return array{jobs_total_count: int, jobs: Job[]} */
	public function fetchJobs(int $page, int $limit): array
	{
		$cacheKey = 'job_list_' . $page . '_' . $limit;

		$jobsData = $this->cache->get($cacheKey, function (ItemInterface $item) use ($page, $limit) {
			$item->expiresAfter(self::CACHE_EXPIRATION);

			try {
				$data = $this->apiClient->fetchJobs($page, $limit);

				// If the API returns an empty payload, exception prevents caching
				if (empty($data['payload'])) {
					throw new ApiException('Empty payload received from API', 500);
				}

				return $data;
			} catch (ApiException $e) {
				// Re-throw the exception to prevent caching the error response
				throw $e;
			}
		});

		$jobs = $this->mapper->bulkMap($jobsData['payload']);

		$totalEntries = $jobsData['meta']['entries_total'];

		return [
			'jobs_total_count' => $totalEntries, //int
			'jobs' => $jobs, // array of Job
		];
	}

}
