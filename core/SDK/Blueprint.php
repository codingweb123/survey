<?php
namespace SDK;
class Blueprint
{
    private static array $query = [];
    private static string $tableName = "";

    public function __construct($tableName)
    {
        self::$tableName = $tableName;
    }
    private static function makeStringQuery(array $args): string
    {
        $subArgs = "";
        $type = $args["count"] > 0 ? $args["type"]."(".$args["count"].")" : $args["type"];
        if (isset($args["unsigned"])) $subArgs .= $args["unsigned"] ? "UNSIGNED " : "";
        if (isset($args["isNotNull"])) $subArgs .= $args["isNotNull"] ? "NOT " : "";
        return "`".$args["name"]."` $type $subArgs NULL";
    }
    public static function id(): void
    {
        self::$query["id"] = [
            "query" => self::makeStringQuery(["name" => "id", "type" => "int", "count" => 11, "unsigned" => 1, "isNotNull" => 1]),
            "primary" => 1
        ];
    }
    public static function string($name): void
    {
        self::$query[$name] = [
            "query" => self::makeStringQuery(["name" => $name, "type" => "varchar", "count" => 255, "isNotNull" => 1])
        ];
    }
    public static function save(): void
    {
        $queryString = "";
        $i = 0;
        foreach (self::$query as $_ => $query)
        {
            $queryString .= (count(self::$query) == $i+1) ? $query["query"] : $query["query"].", ";
            $i++;
        }
        Schema::updateTableQuery(self::$tableName, $queryString);
    }
}