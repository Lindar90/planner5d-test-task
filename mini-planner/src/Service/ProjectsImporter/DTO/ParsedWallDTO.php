<?php

namespace App\Service\ProjectsImporter\DTO;

class ParsedWallDTO
{
    /** @var ParsedPointDTO[] */
    private array $points;

    /**
     * @param ParsedPointDTO[] $points
     */
    public function __construct(array $points)
    {
        $this->points = $points;
    }

    /**
     * @return ParsedPointDTO[]
     */
    public function getPoints(): array
    {
        return $this->points;
    }
}
