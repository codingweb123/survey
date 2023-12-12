<?php
namespace SDK;
use Database\Database;

class Schema
{
    private static array $tables = [];
    public static function create(string $name, $callback): void
    {
        self::$tables[$name] = [
            "query" => "CREATE TABLE `test` {} ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];
        $callback();
    }
    public static function updateTableQuery($name, $query): void
    {
        self::$tables[$name]["query"] = str_replace("{}", "($query)", self::$tables[$name]["query"]);
        foreach (self::$tables as $table)
        {
            Database::query($table["query"]);
        }
    }
    public static function delete(string $name): void
    {
        Database::query("DROP TABLE IF EXISTS `$name`");
    }
    public static function addTable($table): void
    {

    }
}