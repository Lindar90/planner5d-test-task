<?php

namespace App\Tests\Service\ProjectsImporter;

use App\Service\WebPageFetcher;
use PHPUnit\Framework\TestCase;
use App\Service\ProjectsImporter\Planner5DFloorParser;
use Psr\Log\LoggerInterface;

class Planner5DFloorParserTest extends TestCase
{
    public function testGetProjectsList()
    {
        $galleryPageHtml = file_get_contents(__DIR__ . '/fixtures/galleryPageHtml.html');
        $singlePageHtml = file_get_contents(__DIR__ . '/fixtures/singlePageHtml.html');
        $projectDataJson = file_get_contents(__DIR__ . '/fixtures/projectData.json');

        // Todo create before each test
        $webPageFetcher = $this->createMock(WebPageFetcher::class);

        $webPageFetcher->method('fetchContent')
            ->will($this->returnCallback(function($url) use ($singlePageHtml, $projectDataJson) {
                // Check if the argument matches the expected pattern
                if (preg_match('/^https:\/\/planner5d\.com\/api\/project\/(\d+)$/', $url)) {
                    return $projectDataJson;
                }

                return $singlePageHtml;
            }));


        $logger = $this->createMock(LoggerInterface::class);

        $parser = new Planner5DFloorParser($webPageFetcher, $logger);

        $projects = $parser->getProjectsList($galleryPageHtml);

        $this->assertCount(2, $projects);

        // TODO check internal data of the projects
    }

    // TODO testGetNextPageUrl
}
