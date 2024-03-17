<?php

namespace App\Controller;

use App\Service\ProjectsImporter\ProjectsImportManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsImportController extends AbstractController
{
    private const DEFAULT_MAX_PAGE_LIMIT = 5;

    private ProjectsImportManager $projectImportManager;

    public function __construct(ProjectsImportManager $projectImportManager)
    {
        $this->projectImportManager = $projectImportManager;
    }

    /**
     * @Route("/projects/import")
     */
    public function import(Request $request)
    {
        $pagesLimit = $request->query->getInt(
            'pages_limit',
            ProjectsImportManager::DEFAULT_PAGE_LIMIT
        );

        if ($pagesLimit > self::DEFAULT_MAX_PAGE_LIMIT) {
            return new Response(sprintf(
                'The maximum number of pages to import is %s. TODO: add possibility for offset query param.',
                self::DEFAULT_MAX_PAGE_LIMIT
            ), 400);
        }

        $this->projectImportManager->importProjects($pagesLimit);

        return new Response('Projects imported successfully.');
    }
}
