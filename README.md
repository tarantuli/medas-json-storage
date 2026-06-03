# medas-json-storage

Part of the [Medas framework](https://github.com/tarantuli/medas-core).

## Description

A filesystem-backed `StorageController` implementation for `medas-storage-manager`. Each store is a single JSON file on disk; the storage backend is a directory. This makes it suitable for lightweight configuration, fixture, or small dataset persistence where a full SQL database is not needed.

**Architecture:**

`StorageDirectory` is the `Storage` implementation — it wraps a filesystem path. `StorageFile` is the `Store` implementation — each store corresponds to one `<name>.json` file in the directory. `PathBuilder` constructs the full file path as `<directory>/<name>.json`.

`StorageController` registers itself with `StorageManager` on package initialization and handles all `StorageDirectory`-typed storage backends. It implements the full `StorageControllerInterface` contract:

| Action             | File operation                                 |
|--------------------|------------------------------------------------|
| `CreateStore`      | Creates the `.json` file if it does not exist  |
| `InsertRecord`     | Appends a record to the JSON array in the file |
| `UpdateRecord`     | Updates a specific record by id in the file    |
| `UpdateCollection` | Replaces the entire record set                 |
| `GetRecord`        | Reads matching records from the file           |
| `DeleteRecord`     | Removes a record by id from the file           |
| `DeleteStore`      | Deletes the `.json` file from disk             |

All reads and writes go through `medas-json`'s `JsonEncoder`, so binary string values in records are handled correctly.

## Usage

### Package developer context

Register the package and register a `StorageDirectory` with `StorageManager`:

```php
use Medas\JsonStorage\JsonStoragePackage;

JsonStoragePackage::instance();
```

**Registering a storage directory:**

```php
use Medas\JsonStorage\StorageDirectory;
use Medas\StorageManager\StorageManager;
use Medas\Core\Attributes\Service;

#[Service]
readonly class AppBootstrap
{
    public function __construct(
        private StorageManager $storageManager,
    ) {}

    public function boot(): void
    {
        // Register a named storage directory
        $this->storageManager->addStorage(
            new StorageDirectory(
                directory: __DIR__ . '/../../var/data',
                name: 'json-data',
            ),
        );
    }
}
```

The first registered storage is used as the default when no storage is specified in queries.

**Using via `medas-entity-manager`** — annotate entities with `#[Entity(storage: 'json-data')]` to persist them in the registered JSON storage:

```php
use Medas\Core\Interfaces\{HasId, Uuid};
use Medas\EntityManager\Attributes\{Entity, Id};
use Medas\EntityManager\Traits\Timestamps;

#[Entity(store: 'settings', storage: 'json-data')]
class AppSettings implements HasId
{
    use Timestamps;

    #[Id]
    public Uuid $id;

    public string $key;
    public mixed  $value;

    public function id(): Uuid
    {
        return $this->id;
    }
}
```

Records are stored in `var/data/settings.json`. The entity manager handles insert, update, and delete transparently.

**Direct store access via `StorageController`:**

```php
use Medas\JsonStorage\{StorageController, StorageDirectory};
use Medas\Core\Attributes\Service;

#[Service]
readonly class SettingsRepository
{
    public function __construct(
        private StorageController $storageController,
    ) {}

    public function getStore(): \Medas\StorageManager\Interfaces\Store
    {
        // Returns the StorageFile for 'settings' in the default storage directory
        return $this->storageController->store('settings');
    }

    public function storeExists(): bool
    {
        return $this->storageController->hasStore($this->getStore());
    }
}
```

**Deleting a store (the entire `.json` file):**

```php
$store = $this->storageController->store('settings');
$this->storageController->deleteStore($store);
// Deletes var/data/settings.json
```

### Backend user context

**Directory structure** — each store name maps directly to a file:

```
var/data/
  settings.json      # 'settings' store
  cache-entries.json # 'cache-entries' store
  users.json         # 'users' store
```

**File format** — each `.json` file is a structured object containing store metadata and a keyed data map:

```json
{
    "keyName": "id",
    "fieldNames": ["id", "key", "value"],
    "defaults": {"id": null, "key": null, "value": null},
    "data": {
        "550e8400-...": {"key": "theme", "value": "dark"},
        "6ba7b810-...": {"key": "locale", "value": "nl"}
    }
}
```

`keyName` is the primary key field. `fieldNames` lists all fields. `defaults` holds the default value per field applied when a record doesn't explicitly set that field. `data` is keyed by primary key value so individual records can be found without scanning the whole file. Binary fields are wrapped in the `b64:` envelope by `medas-json`.

**Registering multiple directories** — use different `name` values to register more than one JSON storage backend:

```yaml
# config/storage.yaml
storage:
  json-data:
    directory: var/data
  json-fixtures:
    directory: tests/fixtures
```

```php
$storageManager->addStorage(new StorageDirectory('var/data', 'json-data'));
$storageManager->addStorage(new StorageDirectory('tests/fixtures', 'json-fixtures'));
```

Then reference them in entity definitions:

```php
#[Entity(store: 'users', storage: 'json-fixtures')]
class UserFixture implements HasId { /* ... */ }
```

**Limitations** — JSON storage is not suitable for high-concurrency workloads or large datasets. It has no transaction isolation, no indexing, and every read/write loads or rewrites the entire file. Use `medas-pdo-mysql` or `medas-pdo-sqlite` for production workloads requiring these features.
