<?php declare(strict_types = 1);

namespace Tests\Model\Job;

use App\Model\Job\Job;
use DateTime;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{

	public function testFromArray(): void
	{
		$mockApiResponseContent = file_get_contents(__DIR__ . '/../../mock_api_response.json');
		if ($mockApiResponseContent === false) {
			$this->fail('Failed to read mock API response.');
		}

		$data = json_decode($mockApiResponseContent, true);
		if ($data === null) {
			$this->fail('Failed to decode mock API response.');
		}

		$jobData = $data['payload'][0];

		$job = Job::fromArray($jobData);

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

		// Personalist
		$personalist = $job->getPersonalist();
		$this->assertSame(27015, $personalist->getId());
		$this->assertSame('Aleš Bednář', $personalist->getName());
		$this->assertSame('AB', $personalist->getInitials());

		// Contact
		$contact = $job->getContact();
		$this->assertSame('Lukáš Hanko', $contact->getName());
		$this->assertSame('LH', $contact->getInitials());
		$this->assertSame('mlynarcikova.simona@gmail.com', $contact->getEmail());
		$this->assertNull($contact->getPhone());
		$employee = $contact->getEmployee();
		$this->assertSame(27050, $employee->getId());
		$this->assertSame('Lukáš', $employee->getName());
		$this->assertSame('Hanko', $employee->getSurname());
		$this->assertSame('LH', $employee->getInitials());
		$this->assertSame('lukas.hanko2@t-mobile.cz', $employee->getEmail());
		$this->assertStringContainsString('https://app.recruitis.io/image', $employee->getPhotoUrl());
		$this->assertSame('+421 123 456 789', $employee->getPhone());
		$this->assertSame('https://www.linkedin.com/in/lukashlukash/', $employee->getLinkedin());

		// Salary
		$salary = $job->getSalary();
		if ($salary !== null) {
			$this->assertSame('0', $salary->getMin());
			$this->assertSame('35709', $salary->getMax());
			$this->assertSame('CZK', $salary->getCurrency());
			$this->assertSame('month', $salary->getUnit());
			$this->assertFalse($salary->isVisible());
			$this->assertNull($salary->getNote());
			$this->assertFalse($salary->isMinVisible());
			$this->assertFalse($salary->isMaxVisible());
			$this->assertFalse($salary->isRange());
		}
	}

}
