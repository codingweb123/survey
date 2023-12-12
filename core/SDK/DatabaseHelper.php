<?php
namespace SDK;
class DatabaseHelper
{
    public static function patchTables(): void
    {
        $migrations = dirForScan("database","migrations")[1];
        foreach ($migrations as $migration)
        {
            preg_match("/\d{2}_\d{2}_\d{4}_create_(\w+)_table/", $migration,$matchesList);
            if (count($matchesList) == 0)
            {
                ddd("Error during creating database in $migration migration, make sure your migration name like this: 00_00_0000_create_test_table.php");
            }
            $class = "Create".ucwords($matchesList[1])."Table";
            require_once get_path_to_file("database","migrations").$migration;
            $classObject = new $class;
            $classObject->down();
            $classObject->up();
        }
    }
}