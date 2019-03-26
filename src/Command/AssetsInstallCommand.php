<?php declare(strict_types=1);

namespace Becklyn\Mimeo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AssetsInstallCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected static $defaultName = "mimeo:install";


    /**
     * @inheritDoc
     */
    protected function configure ()
    {
        $this
            ->setDescription("Installs the NPM assets into the project.")
            ->addOption("copy", null, InputOption::VALUE_NONE, "Force copying the assets");
    }


    /**
     * @inheritDoc
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Mimeo: Install Assets");
    }
}
