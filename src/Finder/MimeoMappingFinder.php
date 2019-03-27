<?php declare(strict_types=1);

namespace Becklyn\Mimeo\Finder;


class MimeoMappingFinder
{
    /**
     * @var PackageFinder
     */
    private $packageFinder;


    /**
     * @var string[]
     */
    private $log = [];


    /**
     * @param PackageFinder $packageFinder
     */
    public function __construct (PackageFinder $packageFinder)
    {
        $this->packageFinder = $packageFinder;
    }


    /**
     * Returns the mapping
     *
     * @return array
     */
    public function getMapping () : array
    {
        $this->log = [];
        $mapping = [];

        $packageDependencies = $this->packageFinder->getProjectPackage()["dependencies"] ?? [];

        if (empty($packageDependencies))
        {
            $this->log[] = "Project package either doesn't have a package.json or no dependencies in it.";
            return [];
        }


        foreach (\array_keys($packageDependencies) as $dependencyName)
        {
            $dependencyPackage = $this->packageFinder->getDependencyPackage($dependencyName);

            // skip: no mimeo entry
            if (!isset($dependencyPackage["mimeo"]))
            {
                continue;
            }

            // skip: invalid mimeo entry
            if (!\is_array($dependencyPackage["mimeo"]))
            {
                $this->log[] = \sprintf(
                    "Skipping package <fg=yellow>%s</>, as the mimeo entry has invalid structure.",
                    $dependencyName
                );
                continue;
            }

            foreach ($dependencyPackage["mimeo"] as $mimeoName => $originRelativePath)
            {
                $originPath = $this->packageFinder->getDependencyDirectoryPath($dependencyName, $originRelativePath);

                if (!\is_dir($originPath))
                {
                    $this->log[] = \sprintf(
                        "<fg=yellow>%s</>: Skipped <fg=blue>%s</>, as the target is not a directory.",
                        $dependencyName,
                        $mimeoName
                    );
                    continue;
                }

                if (\array_key_exists($mimeoName, $mapping))
                {
                    $this->log[] = \sprintf(
                        "<fg=yellow>%s</>: Skipped <fg=blue>%s</>, as there already is an entry with the same name.",
                        $dependencyName,
                        $mimeoName
                    );
                    continue;
                }

                if (0 === \preg_match('~^[a-z0-9\\-_]+$~', $mimeoName))
                {
                    $this->log[] = \sprintf(
                        "<fg=yellow>%s</>: Skipped <fg=blue>%s</>, because the supported names must only contain of a-z 0-9 '-_'.",
                        $dependencyName,
                        $mimeoName
                    );
                    continue;
                }

                $mapping[$mimeoName] = $originPath;
                $this->log[] = \sprintf(
                    "<fg=yellow>%s</>: Found <fg=blue>%s</> --> <fg=blue>%s</>",
                    $dependencyName,
                    $mimeoName,
                    $originRelativePath
                );
            }
        }

        return $mapping;
    }


    /**
     * @return string[]
     */
    public function getLog () : array
    {
        return $this->log;
    }
}
