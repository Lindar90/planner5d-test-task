<?php

namespace App\Service\ProjectsImporter\DTO;

class ParsedProjectDto
{
    private string $name;

    private int $hits;

    /** @var ParsedRoomDTO[] */
    private array $rooms;

    /**
     * @param string $name
     * @param int $hits
     * @param ParsedRoomDTO[] $rooms
     */
    public function __construct(string $name, int $hits, array $rooms)
    {
        $this->name = $name;
        $this->hits = $hits;
        $this->rooms = $rooms;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    /**
     * @return ParsedRoomDTO[]
     */
    public function getRooms(): array
    {
        return $this->rooms;
    }
}
