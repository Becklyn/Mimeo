<?php declare(strict_types=1);

namespace Tests\Becklyn\Mimeo\Finder;

use Becklyn\Mimeo\Finder\MimeoMappingFinder;
use Becklyn\Mimeo\Finder\PackageFinder;
use PHPUnit\Framework\TestCase;

class MimeoMappingFinderTest extends TestCase
{
    private $fixtures;

    /**
     * @inheritDoc
     */
    protected function setUp ()
    {
        $this->fixtures = \dirname(__DIR__) . "/_fixtures";
    }


    public function testValidProject ()
    {
        $packageFinder = new PackageFinder("{$this->fixtures}/valid_project");
        $mappingFinder = new MimeoMappingFinder($packageFinder);

        $mapping = $mappingFinder->getMapping();
        $log = $mappingFinder->getLog();

        self::assertCount(2, $mapping);
        self::assertArrayHasKey("a", $mapping);
        self::assertArrayHasKey("b", $mapping);

        self::assertTrue($this->hasEntryOnce($log, "a: Skipped invalid.name, because the supported names must only contain of a-z 0-9 '-_'."), "log contains entry about a::invalid.name");
        self::assertTrue($this->hasEntryOnce($log, "b: Skipped c, as the target is not a directory."), "log contains entry about b::c");
        self::assertTrue($this->hasEntryOnce($log, "Skipping package c, as the mimeo entry has invalid structure."), "log contains entry about c");
    }


    /**
     * @param array  $log
     * @param string $match
     *
     * @return bool
     */
    private function hasEntryOnce (array $log, string $match)
    {
        $count = 0;

        foreach ($log as $entry)
        {
            if (\strip_tags($entry) === $match)
            {
                ++$count;
            }
        }

        return 1 === $count;
    }
}
