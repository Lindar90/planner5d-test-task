<?php

namespace App\Service\ProjectsImporter;

use App\Entity\Project;
use App\Entity\ProjectRoom;
use App\Entity\RoomWall;
use App\Entity\WallPoint;
use App\Service\ProjectsImporter\DTO\ParsedProjectDto;
use App\Service\WebPageFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ProjectsImportManager
{
    private FloorPlansParserInterface $floorPlansParser;
    private WebPageFetcher $webPageFetcher;

    private EntityManagerInterface $entityManager;

    private LoggerInterface $logger;

    private ?string $currentUrl = 'https://planner5d.com/gallery/floorplans';
    private int $currentPage = 1;
    public const DEFAULT_PAGE_LIMIT = 3;

    public function __construct(
        FloorPlansParserInterface $floorPlansParser,
        WebPageFetcher $webPageFetcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->floorPlansParser = $floorPlansParser;
        $this->webPageFetcher = $webPageFetcher;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function importProjects(int $pagesLimit = self::DEFAULT_PAGE_LIMIT): void
    {
        do {
            $this->logger->info("Importing projects from page $this->currentPage");

            $htmlContent = $this->webPageFetcher->fetchContent($this->currentUrl);

            $projectsData = $this->floorPlansParser->getProjectsList($htmlContent);

            $this->saveProjects($projectsData);

            $this->logger->info("Saved projects from page $this->currentPage");

            $this->currentUrl = $this->floorPlansParser->getNextPageUrl($htmlContent);
            $this->currentPage++;
        } while ($this->isNextPageAvailable($pagesLimit));
    }

    /**
     * @param ParsedProjectDto[] $projects
     */
    private function saveProjects(array $projects): void
    {
        foreach ($projects as $projectDTO) {
            $project = new Project();
            $project->setName($projectDTO->getName());
            $project->setHits($projectDTO->getHits());

            // Add ROOMS to the PROJECT
            foreach ($projectDTO->getRooms() as $roomDTO) {
                $room = new ProjectRoom();
                $project->addRoom($room);
                $this->entityManager->persist($room);

                // Add WALLS to the ROOM
                foreach ($roomDTO->getWalls() as $wallDTO) {
                    $wall = new RoomWall();
                    $room->addWall($wall);
                    $this->entityManager->persist($wall);

                    // Add POINTS to the WALL
                    foreach ($wallDTO->getPoints() as $pointDTO) {
                        $point = new WallPoint();
                        $point->setPointY($pointDTO->getY());
                        $point->setPointX($pointDTO->getX());

                        $wall->addPoint($point);
                        $this->entityManager->persist($point);
                    }
                }
            }

            $this->entityManager->persist($project);
        }

        $this->entityManager->flush();
    }

    private function isNextPageAvailable(int $pagesLimit): bool
    {
        return $this->currentPage <= $pagesLimit && $this->currentUrl;
    }
}
