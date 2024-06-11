<?php declare(strict_types = 1);

namespace App\Model\Job;

class Salary
{

	private bool $isRange;
	private bool $isMinVisible;
	private bool $isMaxVisible;
	private string $min;
	private string $max;
	private string $currency;
	private string $unit;
	private bool $visible;
	private ?string $note;

	public function __construct(
		bool $isRange,
		bool $isMinVisible,
		bool $isMaxVisible,
		string $min,
		string $max,
		string $currency,
		string $unit,
		bool $visible,
		?string $note
	)
	{
		$this->isRange = $isRange;
		$this->isMinVisible = $isMinVisible;
		$this->isMaxVisible = $isMaxVisible;
		$this->min = $min;
		$this->max = $max;
		$this->currency = $currency;
		$this->unit = $unit;
		$this->visible = $visible;
		$this->note = $note;
	}

	/** @param array<string,mixed> $data */
	public static function fromArray(array $data): Salary
	{
		return new self(
			$data['is_range'],
			$data['is_min_visible'],
			$data['is_max_visible'],
			(string) $data['min'],
			(string) $data['max'],
			$data['currency'],
			$data['unit'],
			$data['visible'],
			$data['note']
		);
	}

	public function isRange(): bool
	{
		return $this->isRange;
	}

	public function isMinVisible(): bool
	{
		return $this->isMinVisible;
	}

	public function isMaxVisible(): bool
	{
		return $this->isMaxVisible;
	}

	public function getMin(): string
	{
		return $this->min;
	}

	public function getMax(): string
	{
		return $this->max;
	}

	public function getCurrency(): string
	{
		return $this->currency;
	}

	public function getUnit(): string
	{
		return $this->unit;
	}

	public function isVisible(): bool
	{
		return $this->visible;
	}

	public function getNote(): ?string
	{
		return $this->note;
	}

}
