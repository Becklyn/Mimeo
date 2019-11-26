<?php declare(strict_types=1);

namespace Becklyn\Mimeo\Finder;

use Becklyn\Mimeo\Exception\InvalidPackageJsonException;

class PackageFinder
{
    /**
     * @var string
     */
    private $projectDir;


    /**
     */
    public function __construct (string $projectDir)
    {
        $this->projectDir = \rtrim($projectDir, "/");
    }


    /**
     * Returns the package.json content of the project itself.
     *
     * @return array
     */
    public function getProjectPackage () : ?array
    {
        return $this->loadFile(null);
    }


    /**
     * Returns the package.json content of a dependency.
     *
     * @return array
     */
    public function getDependencyPackage (string $dependency) : ?array
    {
        return $this->loadFile("node_modules/" . \trim($dependency, "/"));
    }


    /**
     * Returns a path to a directory inside the package.
     */
    public function getDependencyDirectoryPath (string $dependency, string $path) : string
    {
        return "{$this->projectDir}/node_modules/" . \trim($dependency, "/") . "/" . \trim($path, "/");
    }


    /**
     * @param string $path
     *
     * @throws InvalidPackageJsonException
     */
    private function loadFile (?string $path) : array
    {
        $relativePath = null !== $path
            ? "/" . \trim($path, "/")
            : "";

        $filePath = "{$this->projectDir}{$relativePath}/package.json";

        if (!\is_file($filePath) || !\is_readable($filePath))
        {
            return [];
        }

        $data = \json_decode(\file_get_contents($filePath), true);

        if (\JSON_ERROR_NONE !== \json_last_error() || !\is_array($data))
        {
            throw new InvalidPackageJsonException("The package json at {$relativePath}/package.json has invalid content.");
        }

        return $data;
    }
}
