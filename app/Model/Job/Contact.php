<?php declare(strict_types = 1);

namespace App\Model\Job;

class Contact
{

	private string $name;
	private string $initials;
	private string $email;
	private ?string $phone;
	private Employee $employee;

	public function __construct(string $name, string $initials, string $email, ?string $phone, Employee $employee)
	{
		$this->name = $name;
		$this->initials = $initials;
		$this->email = $email;
		$this->phone = $phone;
		$this->employee = $employee;
	}

	/** @param array<string,mixed> $data */
	public static function fromArray(array $data): self
	{
		return new self(
			$data['name'],
			$data['initials'],
			$data['email'],
			$data['phone'],
			Employee::fromArray($data['employee'])
		);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getInitials(): string
	{
		return $this->initials;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPhone(): ?string
	{
		return $this->phone;
	}

	public function getEmployee(): Employee
	{
		return $this->employee;
	}

}
