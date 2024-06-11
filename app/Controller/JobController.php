<?php declare(strict_types = 1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Repository\API\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{

	private const PER_PAGE = 10;

	private JobRepository $jobRepository;

	public function __construct(JobRepository $jobRepository)
	{
		$this->jobRepository = $jobRepository;
	}

	#[Route('/jobs/{page}', name: 'job_list', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
	public function list(int $page = 1): Response
	{
		try {
			$jobsData = $this->jobRepository->fetchJobs($page, self::PER_PAGE);
			$jobs = $jobsData['jobs'];
			$totalPages = ceil($jobsData['jobs_total_count'] / self::PER_PAGE);
		} catch (ApiException $e) {
			$jobs = [];
			$totalPages = 0;
		}

		return $this->render('job/list.html.twig', [
			'jobs' => $jobs,
			'currentPage' => $page,
			'totalPages' => ceil($totalPages / self::PER_PAGE),
		]);
	}

}
