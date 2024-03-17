<?php

namespace App\Service\ProjectsImporter\DTO;

class ParsedRoomDTO
{
    /** @var ParsedWallDTO[] */
    private array $walls;

    /**
     * @param ParsedWallDTO[] $walls
     */
    public function __construct(array $walls)
    {
        $this->walls = $walls;
    }

    /**
     * @return ParsedWallDTO[]
     */
    public function getWalls(): array
    {
        return $this->walls;
    }
}
