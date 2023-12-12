<?php
namespace Templater;
class Templater {
    public function log($message, $type = "info"): bool|null
    {
        [$logPath, $msg] = [get_root_path()."logs".DIRECTORY_SEPARATOR."core.log", "[".strtoupper($type)."] $message\n"];
        if (is_writable($logPath))
        {
            if (!$handle = fopen($logPath, "a+")) return false;
            if (fwrite($handle, $msg) === FALSE) return false;
            fclose($handle);
        }
    }
    public static function compile(): bool
    {
        $templatesViewsDir = dirForScan("resources","views");
        $templateCompiledDir = dirForScan("resources","compiled");
        foreach ($templatesViewsDir[1] as $template)
        {
            $compiledHash = md5($template).".aq.php";
            $handle = fopen($templatesViewsDir[0].$template, "r");
            $handle2 = fopen($templateCompiledDir[0].$compiledHash, "w+");
            if (!$handle) return false;
            self::syntaxToCode($handle, $handle2, $templateCompiledDir[0].$compiledHash, $template);
            fclose($handle);
        }
        return true;
    }
    private static function syntaxToCode($handle, $handle2, $handleFile, $fileName): void
    {
        $fileName = str_replace(".aq.php", "", $fileName);
        $slotFileName = str_replace(DIRECTORY_SEPARATOR, ".", $fileName);
        while (($line = fgets($handle)) !== false) {
            preg_match_all('/{{\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*}}/', $line, $variablesMatches);
            $varMatch = 0;
            if (isset($variablesMatches[1]))
                foreach ($variablesMatches[1] as $key => $match) {
                    $var = $variablesMatches[1][$key];
                    $line = str_replace($variablesMatches[0][$key], "<?= checkVariableAndLoad(\$__AQUA_VARIABLES, \"$var\"); ?>", $line);
                    $varMatch += 1;
                }
            if ($varMatch === 0)
            {
                preg_match_all('/{{\s*(\S.*?)\s*}}/', $line, $linesMatches);
                foreach ($linesMatches[1] as $key => $match) {
                    $var = $linesMatches[1][$key];
                    $line = str_replace($linesMatches[0][$key], "<?= $var ?>", $line);
                }
            }
            preg_match_all('/<aqua-content>(.*?)<\/aqua-content>/', $line, $contentMatches);
            if (isset($contentMatches[1]))
                foreach ($contentMatches[1] as $key => $match) {
                    $line = str_replace($contentMatches[0][$key], "<?= \$__AQUA_VARIABLES[\"slot\"][\"$slotFileName\"] ?? '' ?>", $line);
                }
            preg_match_all('/<aqua-content(.*?)\/>/', $line, $contentTypeTwoMatches);
            if (isset($contentTypeTwoMatches[1]))
                foreach ($contentTypeTwoMatches[1] as $key => $match) {
                    $line = str_replace($contentTypeTwoMatches[0][$key], "<?= \$__AQUA_VARIABLES[\"slot\"][\"$slotFileName\"] ?? '' ?>", $line);
                }
            fwrite($handle2, $line);
        }
        $file = fopen($handleFile, "r+");
        $content = "";
        while (($line = fgets($file)) !== false) {
            $content .= $line;
        }
        fwrite(fopen($handleFile, "w"), self::multipleSlot($content));
        fclose($file);
        $componentFile = fopen($handleFile, "r+");
        $componentContent = "";
        while (($line1 = fgets($componentFile)) !== false) {
            $componentContent .= $line1;
        }
        fwrite(fopen($handleFile, "w"), self::multipleComponent($componentContent));
        fclose($componentFile);
    }
    private static function multipleComponent($componentContent): string
    {
        preg_match_all('/<aqua-component\s+name="([^"]+)"\s+relate="([^"]+)">(.*?)<\/aqua-component>/s', $componentContent, $componentMatches);
        if (isset($componentMatches[1]))
            foreach ($componentMatches[1] as $key => $componentMatch) {
                $name = $componentMatches[1][$key];
                $relate = $componentMatches[2][$key];
                $relates = explode(",", $relate);
                $relations = "";
                foreach ($relates as $key2 => $relate)
                {
                    if ($key2+1 == count($relates)) $relations .= "\"$relate\" => \$$relate";
                    else $relations .= "\"$relate\" => \$$relate, ";
                }
                $contentOfComponent = trim($componentMatches[3][$key]);
                $componentContent = str_replace($componentMatches[0][$key], "$contentOfComponent\n<?php if (getSlot(\$__AQUA_VARIABLES, \"$name\")) view(\"$name\", [$relations, \"slot\" => [\"$name\", \$__AQUA_VARIABLES[\"slot\"][\"$name\"]]]); else view(\"$name\", [$relations]); ?>", $componentContent);
                if ($contentOfComponent != "") $componentContent = self::multipleComponent($componentContent);
            }
        return $componentContent;
    }
    private static function slotCheck(string $pattern, $content): string|null
    {
        $replacedSlots = [];
        $replacedContent = preg_replace_callback($pattern, function ($match) use (&$replacedSlots) {
            $slotComponent = trim($match[1]);
            $slotMatch = trim($match[2]);
            if (in_array($slotComponent, $replacedSlots)) {
                return $match[0];
            }
            $replacedSlots[] = $slotComponent;
            $replacement = "<?php \$__AQUA_VARIABLES[\"slot\"][\"$slotComponent\"] = <<<HTML\n$slotMatch\nHTML; ?>";
            return str_replace($match[0], $replacement, $match[0]);
        }, $content);
        if ($replacedContent !== null) {
            $content = $replacedContent;
        }
        return $content;
    }
    private static function multipleSlot($content): string
    {
        $pattern = '/<aqua-slot\s+component="([^"]+)">(.*?)<\/aqua-slot>/s';
        return self::slotCheck($pattern, $content);
    }
}