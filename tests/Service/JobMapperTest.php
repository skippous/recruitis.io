<?php declare(strict_types = 1);

namespace Tests\Service;

use App\Model\Job\Job;
use App\Service\JobMapper;
use DateTime;
use PHPUnit\Framework\TestCase;

class JobMapperTest extends TestCase
{

	private JobMapper $jobMapper;

	protected function setUp(): void
	{
		$this->jobMapper = new JobMapper();
	}

	public function testMap(): void
	{
		$mockApiResponseContent = file_get_contents(__DIR__ . '/../mock_api_response.json');
		if ($mockApiResponseContent === false) {
			$this->fail('Failed to read mock API response.');
		}

		$mockApiResponse = json_decode($mockApiResponseContent, true);
		if ($mockApiResponse === null) {
			$this->fail('Failed to decode mock API response.');
		}

		$jobData = $mockApiResponse['payload'][0];

		$job = $this->jobMapper->map($jobData);

		$this->assertInstanceOf(Job::class, $job);
		$this->assertSame(431912, $job->getJobId());
		$this->assertSame('6D65xKemuldGqfr6xRMq1UtMcmNURQr3', $job->getSecuredId());
		$this->assertNull($job->getPublicId());
		$this->assertSame(1, $job->getAccessState());
		$this->assertFalse($job->isDraft());
		$this->assertTrue($job->isActive());
		$this->assertSame('Manažer segmentu trhu ve Fiber tribu KLON LHO 17 (m\\z) test&test (m/z) test|@![]();test', $job->getTitle());
		$this->assertStringContainsString('<p><strong>“Téčko” tedy T-Mobile a Slovak Telekom zahájilo agilní transformaci a vy se na ni můžete podílet s námi!', $job->getDescription());
		$this->assertNull($job->getDateEnd());
		$this->assertNull($job->getDateClosed());
		$this->assertEquals(new DateTime('2023-06-30 09:28:00'), $job->getDateCreated());
		$this->assertEquals(new DateTime('2023-06-30 09:44:15'), $job->getLastUpdate());
		// Add more assertions for other fields if necessary
	}

	public function testBulkMap(): void
	{
		$mockApiResponseContent = file_get_contents(__DIR__ . '/../mock_api_response.json');
		if ($mockApiResponseContent === false) {
			$this->fail('Failed to read mock API response.');
		}

		$data = json_decode($mockApiResponseContent, true);
		if ($data === null) {
			$this->fail('Failed to decode mock API response.');
		}

		$jobsData = $data['payload'];

		$jobs = $this->jobMapper->bulkMap($jobsData);

		$this->assertIsArray($jobs);
		$this->assertCount(count($jobsData), $jobs);
		$this->assertContainsOnlyInstancesOf(Job::class, $jobs);

		$firstJob = $jobs[0];
		$this->assertSame(431912, $firstJob->getJobId());
		$this->assertSame('6D65xKemuldGqfr6xRMq1UtMcmNURQr3', $firstJob->getSecuredId());
		// Add more assertions for other fields if necessary
	}

}
