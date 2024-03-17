<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Service\ProjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    private ProjectRepository $projectRepository;

    private ProjectManager $projectManager;


    public function __construct(
        ProjectRepository $projectRepository,
        ProjectManager $projectManager
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectManager = $projectManager;
    }

    /**
     * @Route("/", name="app_projects_list")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        [
            'projects' => $projects,
            'totalPages' => $totalPages,
        ] = $this->projectManager->getPaginatedProjects($page, $limit);

        return $this->render('project-list.html.twig', [
            'projects' => $projects,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * @Route("/projects/{id}", requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        $rooms = $this->projectManager->getRoomsWithWallsAndPointsByProject($id);

        return $this->render('project-details.html.twig', [
            'project' => $project,
            'rooms' => $rooms,
        ]);
    }
}
