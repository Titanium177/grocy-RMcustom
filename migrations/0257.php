<?php

// This is executed inside DatabaseMigrationService class/context

use Grocy\Services\DatabaseService;

// Set default category for all existing recipes without a category
$db = DatabaseService::GetInstance();

$db->ExecuteDbStatement("
	UPDATE recipes
	SET category_id = 1
	WHERE category_id IS NULL
		OR category_id = 0;
");
