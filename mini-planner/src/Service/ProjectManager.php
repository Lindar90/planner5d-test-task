<?php

namespace App\Service;

use App\Entity\ProjectRoom;
use App\Repository\ProjectRepository;
use App\Repository\ProjectRoomRepository;

class ProjectManager
{
    private ProjectRoomRepository $projectRoomRepository;

    private ProjectRepository $projectRepository;

    public function __construct(
        ProjectRoomRepository $projectRoomRepository,
        ProjectRepository $projectRepository
    ) {
        $this->projectRoomRepository = $projectRoomRepository;
        $this->projectRepository = $projectRepository;
    }

    public function getRoomsWithWallsAndPointsByProject(int $projectId): array
    {
        /** @var ProjectRoom[] $rawData */
        $rawData = $this->projectRoomRepository->getRoomsWithWallsAndPointsByProject($projectId);

        $rooms = [];

        foreach ($rawData as $room) {
            $rooms[$room->getId()] = [
                'id' => $room->getId(),
                'walls' => []
            ];

            foreach ($room->getWalls() as $wall) {
                $rooms[$room->getId()]['walls'][$wall->getId()] = [
                    'id' => $wall->getId(),
                    'points' => []
                ];

                foreach ($wall->getPoints() as $point) {
                    $rooms[$room->getId()]['walls'][$wall->getId()]['points'][] = [
                        'x' => $point->getPointX(),
                        'y' => $point->getPointY(),
                    ];
                }
            }
        }

        return $rooms;
    }

    public function getPaginatedProjects(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $projects = $this->projectRepository->findBy([], [], $limit, $offset);
        $totalProjects = $this->projectRepository->count([]);
        $totalPages = ceil($totalProjects / $limit);

        return [
            'projects' => $projects,
            'totalPages' => $totalPages,
        ];
    }
}
