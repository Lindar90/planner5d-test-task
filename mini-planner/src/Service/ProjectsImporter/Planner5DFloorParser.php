<?php

namespace App\Service\ProjectsImporter;

use App\Service\ProjectsImporter\DTO\ParsedPointDTO;
use App\Service\ProjectsImporter\DTO\ParsedProjectDto;
use App\Service\ProjectsImporter\DTO\ParsedRoomDTO;
use App\Service\ProjectsImporter\DTO\ParsedWallDTO;
use App\Service\WebPageFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

class Planner5DFloorParser implements FloorPlansParserInterface
{
    private WebPageFetcher $webPageFetcher;

    private LoggerInterface $logger;

    public function __construct(WebPageFetcher $webPageFetcher, LoggerInterface $logger)
    {
        $this->webPageFetcher = $webPageFetcher;
        $this->logger = $logger;
    }

    /**
     * @return ParsedProjectDto[]
     */
    public function getProjectsList(string $htmlContent): array
    {
        $projects = [];

        $this->logger->info('Fetching project URLs');
        $projectUrls = $this->getProjectUrls($htmlContent);

        foreach ($projectUrls as $projectUrl) {
            try {
                $this->logger->info("Fetching HTML for project: $projectUrl");

                $projectContent = $this->webPageFetcher->fetchContent($projectUrl);

                $name = $this->getProjectName($projectContent);
                $hits = $this->getProjectHits($projectContent);
                $rooms = $this->getProjectRooms($projectContent);

                $projects[] = new ParsedProjectDto($name, $hits, $rooms);

                $this->logger->info("Content successfully parsed for project: $projectUrl");
            } catch (\Exception $e) {
                $this->logger->error("Error parsing project $projectUrl with error {$e->getMessage()}");
                throw $e;
            }
        }

        return $projects;
    }

    public function getNextPageUrl(string $htmlContent): ?string
    {
        $crawler = new Crawler($htmlContent);

        $nextPages = $crawler->filter('div.pagination a.active')->nextAll();

        if ($nextPages->count() === 0) {
            return null;
        }

        return $nextPages->first()->attr('href');
    }

    /**
     * @return string[]
     */
    private function getProjectUrls(string $content): array
    {
        $crawler = new Crawler($content);

        return $crawler->filter('h3 a')->each(function (Crawler $node) {
            return $node->attr('href');
        });
    }

    private function getProjectName(string $content): string
    {
        $crawler = new Crawler($content);

        return $crawler->filter('h1')->text();
    }

    private function getProjectHits(string $content): int
    {
        $crawler = new Crawler($content);

        return (int) $crawler
            ->filter('div.level-item .has-text-grey-light span')
            ->last()
            ->text();
    }

    private function getProjectRooms(string $content): array
    {
        $projectId = $this->getProjectId($content);
        $projectData = $this->getProjectData($projectId);

        $floorData = $this->getFloorData($projectData);

        return $this->getRooms($floorData);
    }

    private function getProjectId(string $content): string
    {
        $crawler = new Crawler($content);


        $threeDUrl = $crawler->filter('a:contains("Open in 3D")')->first()->attr('href');
        $urlComponents = parse_url($threeDUrl);

        if (!isset($urlComponents['query'])) {
            throw new \RuntimeException('Invalid 3D URL');
        }

        parse_str($urlComponents['query'], $queryParams);

        if (!isset($queryParams['key'])) {
            throw new \RuntimeException('Invalid 3D URL');
        }

        return $queryParams['key'];
    }

    private function getProjectData(string $projectId): array
    {
        $projectApiUrl = "https://planner5d.com/api/project/{$projectId}";
        return json_decode($this->webPageFetcher->fetchContent($projectApiUrl), true);
    }

    private function getFloorData(array $projectData): array
    {
        $floor = null;

        foreach ($projectData['items'][0]['data']['items'] as $item) {
            if ($item['className'] === 'Floor') {
                $floor = $item;
                break;
            }
        }

        if ($floor === null) {
            throw new \RuntimeException('Floor not found');
        }

        return $floor;
    }

    private function getRooms(array $floor): array
    {
        $rooms = [];

        foreach ($floor['items'] as $item) {
            if ($item['className'] === 'Room') {
                $walls = $this->getWalls($item);
                $rooms[] = new ParsedRoomDTO($walls);
            }
        }

        return $rooms;
    }

    private function getWalls(array $room): array
    {
        $walls = [];

        foreach ($room['items'] as $item) {
            if ($item['className'] === 'Wall') {
                $points = $this->getWallPoints($item);
                $walls[] = new ParsedWallDTO($points);
            }
        }

        return $walls;
    }

    private function getWallPoints(array $wall): array
    {
        $points = [];

        foreach ($wall['items'] as $item) {
            if ($item['className'] === 'Point') {
                $points[] = new ParsedPointDTO($item['x'], $item['y']);
            }
        }

        return $points;
    }
}
