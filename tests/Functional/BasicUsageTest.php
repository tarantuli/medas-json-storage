<?php

declare(strict_types=1);

namespace Medas\JsonStorageTest\Functional;

use Medas\JsonStorageTest\ImportedTest;

class BasicUsageTest extends ImportedTest
{
    public function testMigration(): void
    {
        $testPath = 'tests/TestStorage/new_stored_entities.json';

        if (file_exists($testPath)) {
            unlink($testPath);
        }

        $migration = $this->createMigrationClassContent('Migrations');

        self::assertStringContainsString(
            '$unitOfWork->addAction(new CreateFile("default", "tests/TestStorage\\\\new_stored_entities.json"',
            $migration
        );

        $this->executeMigration($migration);

        self::assertFileExists($testPath);
    }
}
