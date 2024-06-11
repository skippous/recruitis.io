<?php declare(strict_types = 1);

namespace App\Service;

use App\Exception\ApiException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class RecruitisApiClient
{

	private HttpClientInterface $client;
	private LoggerInterface $logger;
	private string $apiToken;
	private string $baseUrl;

	public function __construct(HttpClientInterface $client, LoggerInterface $logger, string $apiToken, string $baseUrl)
	{
		$this->client = $client;
		$this->logger = $logger;
		$this->apiToken = $apiToken;
		$this->baseUrl = $baseUrl;
	}

	/** @return array<string, mixed> */
	public function fetchJobs(int $page = 1, int $limit = 10): array
	{
		try {
			$response = $this->client->request('GET', $this->baseUrl . '/jobs', [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->apiToken,
				],
				'query' => [
					'page' => $page,
					'limit' => $limit,
				],
			]);
			return $response->toArray();
		} catch (Throwable $e) {
			if ($e instanceof HttpExceptionInterface) {
				$statusCode = $e->getResponse()->getStatusCode();
				$this->logger->error('Failed to fetch jobs from API', [
					'status_code' => $statusCode,
					'exception' => $e,
				]);
				throw new ApiException('Failed to fetch jobs from API', $statusCode, $e);
			} else {
				$this->logger->error('Failed to fetch jobs from API', ['exception' => $e]);
				throw new ApiException('Failed to fetch jobs from API', 0, $e);
			}
		}
	}

}
