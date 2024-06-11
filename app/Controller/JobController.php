<?php declare(strict_types = 1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Repository\API\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{

	private const PER_PAGE = 5;

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
			'totalPages' => $totalPages,
		]);
	}

	#[Route('/jobs-vue/{page}', name: 'job_list_vue', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
	public function listVue(int $page = 1): Response
	{
		return $this->render('job/list-vue.html.twig', []);
	}

	#[Route('/api/jobs', name: 'api_job_list')]
	public function apiList(Request $request): JsonResponse
	{
		$page = (int) $request->query->get('page', 1);

		try {
			$jobsData = $this->jobRepository->fetchJobs($page, self::PER_PAGE);
			$jobsArray = array_map(fn($job) => $job->toArray(), $jobsData['jobs']);
			return new JsonResponse([
				'jobs_total_count' => $jobsData['jobs_total_count'],
				'jobs' => $jobsArray,
			]);
		} catch (ApiException $e) {
			// Return a proper JSON response with the error details
			return new JsonResponse([
				'error' => 'Failed to fetch jobs from the API',
				'message' => $e->getMessage(),
				'status_code' => $e->getCode(),
			], $e->getCode());
		}
	}

}
