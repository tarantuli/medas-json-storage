<?php

declare(strict_types=1);

namespace Medas\JsonStorageTest\Functional;

use Medas\JsonStorage\Definitions\Field;
use Medas\JsonStorage\Exceptions\UnknownField;
use Medas\JsonStorage\JsonDirectory;
use Medas\JsonStorage\JsonFile;
use Medas\StorageManager\Structure\Blueprint\Type;
use PHPUnit\Framework\TestCase;

class BasicUsageTest extends TestCase
{
    private const STORAGE = __DIR__ . DIRECTORY_SEPARATOR . 'basic_usage_test';
    private const STORE1 = 'people';

    private const STORE1_FILENAME = self::STORAGE . DIRECTORY_SEPARATOR . self::STORE1 . '.json';

    public function testCleanUp(): void
    {
        if (file_exists(self::STORE1_FILENAME)) {
            unlink(self::STORE1_FILENAME);
        }

        if (file_exists(self::STORAGE)) {
            rmdir(self::STORAGE);
        }

        self::assertFalse(file_exists(self::STORE1_FILENAME));
        self::assertFalse(file_exists(self::STORAGE));
    }

    /** @depends testCleanUp */
    public function testCreateStorage(): JsonDirectory
    {
        $directory = new JsonDirectory(self::STORAGE);

        self::assertTrue(file_exists(self::STORAGE));

        return $directory;
    }

    /** @depends testCreateStorage */
    public function testCreateStore(JsonDirectory $directory): JsonFile
    {
        $file = $directory->store(self::STORE1);

        self::assertTrue(file_exists(self::STORE1_FILENAME));

        return $file;
    }

    /** @depends testCreateStore */
    public function testCreateRecordUnknownField(JsonFile $file): void
    {
        $this->expectException(UnknownField::class);
        $file->insertValues(['name' => 'Patrick']);
    }

    /** @depends testCreateStore */
    public function testAddFieldAndCreateRecord(JsonFile $file): JsonFile
    {
        $file->addField(new Field('name', Type::Text));
        $recordSet = $file->insertValues(['name' => 'John']);

        $record = $recordSet->fetchRecord();

        self::assertEquals(1, $record->id);

        return $file;
    }

    /** @depends testAddFieldAndCreateRecord */
    public function testFetchRecord(JsonFile $file): JsonFile
    {
        $record = $file->fetchRecord(['name' => 'John']);
        self::assertEquals(1, $record->id);

        return $file;
    }

    /** @depends testAddFieldAndCreateRecord */
    public function testFetchRecordFromDisk(JsonFile $file): JsonFile
    {
        $file->read();
        $record = $file->fetchRecord(['name' => 'John']);
        self::assertEquals(1, $record->id);

        return $file;
    }

    /** @depends testAddFieldAndCreateRecord */
    public function testNonConsecutiveIdValue(JsonFile $file): JsonFile
    {
        $file->insertValues(['name' => 'Peter'], 10);
        $file->read();

        $record = $file->fetchRecord(['name' => 'Peter']);
        self::assertEquals(10, $record->id);

        return $file;
    }

    public function testPostCleanUp(): void

    {
        $this->testCleanUp();
    }
}
