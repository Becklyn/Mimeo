<?php declare(strict_types=1);

namespace Becklyn\Mimeo\Command;

use Becklyn\Mimeo\Copy\AssetsCopy;
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
     * @var AssetsCopy
     */
    private $assetsCopy;


    /**
     * @inheritDoc
     */
    public function __construct (AssetsCopy $assetsCopy)
    {
        parent::__construct();
        $this->assetsCopy = $assetsCopy;
    }


    /**
     * @inheritDoc
     */
    protected function configure () : void
    {
        $this
            ->setDescription("Installs the NPM assets into the project.")
            ->addOption("copy", null, InputOption::VALUE_NONE, "Force copying the assets")
            ->addOption("target", null, InputOption::VALUE_REQUIRED, "Target directory. Relative to the project dir.", "build/mayd");
    }


    /**
     * @inheritDoc
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Mimeo: Install Assets");

        $hardCopy = $input->getOption("copy") || ("0" === $_ENV["APP_DEBUG"]);
        $target = $input->getOption("target");

        return $this->assetsCopy->copyAll($io, $hardCopy, $target) ? 0 : 1;
    }
}
