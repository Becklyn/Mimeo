<?php declare(strict_types=1);

namespace Becklyn\Mimeo\Copy;

use Becklyn\Mimeo\Finder\MimeoMappingFinder;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class AssetsCopy
{
    /**
     * @var MimeoMappingFinder
     */
    private $mimeoMappingFinder;


    /**
     * @var Filesystem
     */
    private $filesystem;


    /**
     * @var string
     */
    private $projectDir;


    /**
     */
    public function __construct (MimeoMappingFinder $mimeoMappingFinder, Filesystem $filesystem, string $projectDir)
    {
        $this->mimeoMappingFinder = $mimeoMappingFinder;
        $this->filesystem = $filesystem;
        $this->projectDir = \rtrim($projectDir, "/");
    }


    /**
     * Copies all assets.
     *
     * @param bool $hardCopy if true, will create a hard copy, if false will create an absolute link
     */
    public function copyAll (SymfonyStyle $io, bool $hardCopy, string $target) : bool
    {
        $target = \trim($target);
        $targetDir = "{$this->projectDir}/{$target}";

        $io->comment(\sprintf(
            "Copying assets using the <fg=yellow>%s</> method.",
            $hardCopy ? "hard copy" : "symlink"
        ));

        $io->section("Fetch Mimeo Mapping");
        $mimeoMapping = $this->mimeoMappingFinder->getMapping();
        $io->listing($this->mimeoMappingFinder->getLog());

        $io->section("Remove Target Dir");
        $io->text("Removing the target dir at <fg=yellow>{$target}</>");
        $this->filesystem->remove($targetDir);

        $io->section("Performing file system actions");

        if ($hardCopy)
        {
            $fileSystemAction = function (string $name, string $origin) use ($targetDir) : void
            {
                $this->filesystem->mirror($origin, "{$targetDir}/{$name}");
            };
        }
        else
        {
            $fileSystemAction = function (string $name, string $origin) use ($targetDir) : void
            {
                $relativeOrigin = $this->filesystem->makePathRelative($origin, $targetDir);
                $this->filesystem->symlink($relativeOrigin, "{$targetDir}/{$name}");
            };
        }

        $this->performFileSystemActions(
            $fileSystemAction,
            $hardCopy ? "Copying" : "Symlinking",
            $io,
            $mimeoMapping
        );

        return true;
    }


    /**
     */
    private function performFileSystemActions (callable $action, string $logLabel, SymfonyStyle $io, array $packages) : void
    {
        $log = [];

        foreach ($packages as $name => $origin)
        {
            $action($name, $origin);

            $log[] = \sprintf(
                "%s to <fg=blue>%s</> from <fg=blue>%s</>",
                $logLabel,
                $name,
                $this->filesystem->makePathRelative($origin, $this->projectDir)
            );
        }

        $io->listing($log);
    }
}
