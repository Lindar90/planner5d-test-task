<?php

namespace App\Service\ProjectsImporter;

use App\Service\ProjectsImporter\DTO\ParsedProjectDto;

interface FloorPlansParserInterface
{
    /**
     * @return ParsedProjectDto[]
     */
    public function getProjectsList(string $htmlContent): array;

    public function getNextPageUrl(string $htmlContent): ?string;
}
