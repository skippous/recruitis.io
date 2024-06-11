<?php declare(strict_types = 1);

namespace App\Model\Job;

class Employee
{

	private int $id;
	private string $name;
	private string $surname;
	private string $initials;
	private string $email;
	private string $photoUrl;
	private string $phone;
	private ?string $linkedin;

	public function __construct(
		int $id,
		string $name,
		string $surname,
		string $initials,
		string $email,
		string $photoUrl,
		string $phone,
		?string $linkedin
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->surname = $surname;
		$this->initials = $initials;
		$this->email = $email;
		$this->photoUrl = $photoUrl;
		$this->phone = $phone;
		$this->linkedin = $linkedin;
	}

	/** @param array<string,mixed> $data */
	public static function fromArray(array $data): self
	{
		return new self(
			$data['id'],
			$data['name'],
			$data['surname'],
			$data['initials'],
			$data['email'],
			$data['photo_url'],
			$data['phone'],
			$data['linkedin'] ?? null
		);
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getSurname(): string
	{
		return $this->surname;
	}

	public function getInitials(): string
	{
		return $this->initials;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPhotoUrl(): string
	{
		return $this->photoUrl;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function getLinkedin(): ?string
	{
		return $this->linkedin;
	}

}
