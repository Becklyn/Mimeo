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
    protected function setUp ()
    {
        $this->fixtures = \dirname(__DIR__) . "/_fixtures";
    }


    public function testProject ()
    {
        $finder = new PackageFinder("{$this->fixtures}/valid_project");
        $dependencies = $finder->getProjectPackage();

        self::assertSame([
            "dependencies" => [
                "a" => "1.0",
                "b" => "2.0",
                "c" => "3.0",
            ]
        ], $dependencies);
    }


    public function testBrokenDependency ()
    {
        $this->expectException(InvalidPackageJsonException::class);
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        $finder->getDependencyPackage("broken");
    }


    public function testInvalidTypeDependency ()
    {
        $this->expectException(InvalidPackageJsonException::class);
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        $finder->getDependencyPackage("invalid_type");
    }


    public function testMissingDependency ()
    {
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        self::assertSame([], $finder->getDependencyPackage("missing"));
    }


    public function testValidDependency ()
    {
        $finder = new PackageFinder("{$this->fixtures}/dependency_parse");
        self::assertSame(["name" => "test"], $finder->getDependencyPackage("valid"));
    }
}
