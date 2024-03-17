<?php

namespace App\Command;

use App\Service\ProjectsImporter\ProjectsImportManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PlannerImportProjectsCommand extends Command
{
    protected static $defaultName = 'planner:import-projects';

    protected static $defaultDescription = 'Add a short description for your command';

    private ProjectsImportManager $projectImportManager;

    public function __construct(
        ?string $name = null,
        ProjectsImportManager $projectImportManager
    ) {
        parent::__construct($name);
        $this->projectImportManager = $projectImportManager;
    }

    protected function configure(): void
    {
        $this->addOption(
            'numberOfPages',
            null,
            InputOption::VALUE_OPTIONAL,
            'How many pages to parse?',
            ProjectsImportManager::DEFAULT_PAGE_LIMIT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pagesLimit = (int) $input->getOption('numberOfPages');

        try {
            $this->projectImportManager->importProjects($pagesLimit);
        } catch (Exception $e) {
            $io->error('An error occurred while importing projects: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success('Projects imported successfully.');

        return Command::SUCCESS;
    }
}
