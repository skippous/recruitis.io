<?php declare(strict_types = 1);

namespace App\Model\Job;

class Personalist
{

	private int $id;
	private string $name;
	private string $initials;

	public function __construct(int $id, string $name, string $initials)
	{
		$this->id = $id;
		$this->name = $name;
		$this->initials = $initials;
	}

	/** @param array<string,mixed> $data */
	public static function fromArray(array $data): Personalist
	{
		return new self($data['id'], $data['name'], $data['initials']);
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getInitials(): string
	{
		return $this->initials;
	}

}
