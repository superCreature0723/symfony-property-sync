// src/Command/SyncPropertiesCommand.php
namespace App\Command;

use App\Service\PropertyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncPropertiesCommand extends Command
{
    protected static $defaultName = 'app:sync-properties';

    public function __construct(private readonly PropertyService $propertyService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronizes property data from external APIs.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->propertyService->fetchProperties();
        $output->writeln('Properties synchronized successfully.');
        return Command::SUCCESS;
    }
}
