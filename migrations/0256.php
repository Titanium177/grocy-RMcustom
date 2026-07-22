<?php

// This is executed inside DatabaseMigrationService class/context

use Grocy\Services\DatabaseService;

$db = DatabaseService::GetInstance()->GetDbConnectionRaw();

// Determine default category name based on configured locale
$locale = 'en';
if (defined('GROCY_DEFAULT_LOCALE'))
{
    $locale = GROCY_DEFAULT_LOCALE;
}
elseif (function_exists('Setting'))
{
    // Setting() defines the constant when called with a default value
    Setting('DEFAULT_LOCALE', 'en');
    if (defined('GROCY_DEFAULT_LOCALE'))
    {
        $locale = GROCY_DEFAULT_LOCALE;
    }
}

switch (strtolower($locale))
{
    case 'de':
    case 'de_de':
        $defaultCategoryName = 'Kochrezepte';
        break;
    default:
        $defaultCategoryName = 'Cooking Recipes';
        break;
}

// Create table
$db->exec("CREATE TABLE IF NOT EXISTS recipe_categories (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    name TEXT NOT NULL UNIQUE,
    sort_number INTEGER DEFAULT 0,
    row_created_timestamp DATETIME DEFAULT (datetime('now', 'localtime'))
);");

// Insert default category (use INSERT OR IGNORE to avoid duplicate on re-run)
$stmt = $db->prepare('INSERT OR IGNORE INTO recipe_categories (id, name, sort_number) VALUES (:id, :name, :sort_number)');
$stmt->execute([':id' => 1, ':name' => $defaultCategoryName, ':sort_number' => 0]);

// Alter recipes table to add category_id if not exists
try
{
    $db->exec("ALTER TABLE recipes ADD COLUMN category_id INTEGER DEFAULT 1;");
}
catch (\PDOException $ex)
{
    // SQLite will throw if column already exists; ignore
}

// Create index if not exists
$db->exec("CREATE INDEX IF NOT EXISTS ix_recipes_category ON recipes (category_id);");
