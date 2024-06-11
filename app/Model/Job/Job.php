<?php declare(strict_types = 1);

namespace App\Model\Job;

use DateTime;

class Job
{

	private int $jobId;
	private string $securedId;
	private ?string $publicId;
	private int $accessState;
	private bool $draft;
	private bool $active;
	private string $title;
	private string $description;
	private ?DateTime $dateEnd;
	private ?DateTime $dateClosed;
	private ?DateTime $dateCreated;
	private ?DateTime $lastUpdate;
	private Personalist $personalist;
	private Contact $contact;
	private ?Salary $salary;

	public function __construct(
		int $jobId,
		string $securedId,
		?string $publicId,
		int $accessState,
		bool $draft,
		bool $active,
		string $title,
		string $description,
		?DateTime $dateEnd,
		?DateTime $dateClosed,
		?DateTime $dateCreated,
		?DateTime $lastUpdate,
		Personalist $personalist,
		Contact $contact,
		?Salary $salary,
	)
	{
		$this->jobId = $jobId;
		$this->securedId = $securedId;
		$this->publicId = $publicId;
		$this->accessState = $accessState;
		$this->draft = $draft;
		$this->active = $active;
		$this->title = $title;
		$this->description = $description;
		$this->dateEnd = $dateEnd;
		$this->dateClosed = $dateClosed;
		$this->dateCreated = $dateCreated;
		$this->lastUpdate = $lastUpdate;
		$this->personalist = $personalist;
		$this->contact = $contact;
		$this->salary = $salary;
	}

	/** @param array<string,mixed> $data */
	public static function fromArray(array $data): self
	{
		return new self(
			$data['job_id'],
			$data['secured_id'],
			$data['public_id'],
			$data['access_state'],
			$data['draft'],
			$data['active'],
			$data['title'],
			$data['description'],
			$data['date_end'] ? new DateTime($data['date_end']) : null,
			$data['date_closed'] ? new DateTime($data['date_closed']) : null,
			new DateTime($data['date_created']),
			new DateTime($data['last_update']),
			Personalist::fromArray($data['personalist']),
			Contact::fromArray($data['contact']),
			$data['salary'] ? Salary::fromArray($data['salary']) : null,
		);
	}

	public function getJobId(): int
	{
		return $this->jobId;
	}

	public function getSecuredId(): string
	{
		return $this->securedId;
	}

	public function getPublicId(): ?string
	{
		return $this->publicId;
	}

	public function getAccessState(): int
	{
		return $this->accessState;
	}

	public function isDraft(): bool
	{
		return $this->draft;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getDateEnd(): ?DateTime
	{
		return $this->dateEnd;
	}

	public function getDateClosed(): ?DateTime
	{
		return $this->dateClosed;
	}

	public function getDateCreated(): ?DateTime
	{
		return $this->dateCreated;
	}

	public function getLastUpdate(): ?DateTime
	{
		return $this->lastUpdate;
	}

	public function getPersonalist(): Personalist
	{
		return $this->personalist;
	}

	public function getContact(): Contact
	{
		return $this->contact;
	}

	public function getSalary(): ?Salary
	{
		return $this->salary;
	}

}
