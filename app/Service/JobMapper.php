<?php declare(strict_types = 1);

namespace App\Service;

use App\Model\Job\Job;

final class JobMapper
{

	/** @param array<string,mixed> $data */
	public function map(array $data): Job
	{
		return Job::fromArray($data);
	}

	/**
	 * @param array<string,mixed> $data
	 * @return array<Job>
	 */
	public function bulkMap(array $data): array
	{
		return array_map([self::class, 'map'], $data);
	}

}
