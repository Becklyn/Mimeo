<?php declare(strict_types=1);

namespace Tests\Becklyn\Mimeo\Finder;

use Becklyn\Mimeo\Exception\InvalidPackageJsonException;
use Becklyn\Mimeo\Finder\PackageFinder;
use PHPUnit\Framework\TestCase;

class PackageFinderTest extends TestCase
{
    private $fixtures;

    /**
     * @inheritDoc
     */
    protected function setUp () : void
    {
        $this->fixtures = \dirname(__DIR__) . "/_fixtures";
    }


    public function testProject () : void
    {
        $finder = new PackageFinder("{$this->fixtures}/valid_project");
        $dependencies = $finder->getProjectPackage();

        static::assertSame([
            "dependencies" => [
                "a" => "1.0",
                "b" => "2.0",
                "c" => "3.0",
            ],
        ], $dependencies);
    }


    public function testBrokenDependency () : void
    {
        $this->expectException(InvalidPackageJsonException::class);
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        $finder->getDependencyPackage("broken");
    }


    public function testInvalidTypeDependency () : void
    {
        $this->expectException(InvalidPackageJsonException::class);
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        $finder->getDependencyPackage("invalid_type");
    }


    public function testMissingDependency () : void
    {
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        static::assertSame([], $finder->getDependencyPackage("missing"));
    }


    public function testValidDependency () : void
    {
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        static::assertSame(["name" => "test"], $finder->getDependencyPackage("valid"));
    }
}
