<?php
/**
 * @param $name
 * @return void
 */
function component($name): void
{
    include getcwd() . "resources" . DIRECTORY_SEPARATOR . "compiled" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "$name.aq.php";
}
/**
 * @param string $viewname
 * @param $vars
 * @return void
 */
function view(string $viewName, $vars = null): void
{
    $viewName = md5((str_contains($viewName, ".") ? str_replace(".", DIRECTORY_SEPARATOR, $viewName) : $viewName).".aq.php");
    $path = getcwd() . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "compiled" . DIRECTORY_SEPARATOR . "$viewName.aq.php";
    if (file_exists($path)) {
        if (!$vars) include_once $path;
        else {
            $__AQUA_VARIABLES = [];
            foreach ($vars as $key => $var) {
                global $$key;
                if ($key == "slot") $__AQUA_VARIABLES[$key][$var[0]] = $var[1];
                else $__AQUA_VARIABLES[$key] = $var;
            }
            include_once $path;
        }
    } else ddd("Error: view $viewName cannot be loaded, file not found resources/views/$viewName.aq.php");
}
/**
 * @param array $__AQUA_VARIABLES
 * @param string $name
 * @return bool
 */
function getSlot(array $__AQUA_VARIABLES, string $name): bool
{
    return isset($__AQUA_VARIABLES["slot"][$name]);
}
/**
 * @param string $message
 * @return void
 */
function flashMessage(string $message): void
{
    $_SESSION["flash_message"] = $message;
}
/**
 * @param bool $sleep
 * @return void
 */
function removeFlashMessage(bool $sleep = true): void
{
    if ($sleep) usleep(200);
    unset($_SESSION["flash_message"]);
}
/**
 * @param $var
 * @return bool|string
 */
function echoVarOrJson($var): bool|string
{
    return is_string($var) ? $var : json_encode($var, true);
}
/**
 * @param $variables
 * @param $variable
 * @return false|string
 */
function checkVariableAndLoad($variables, $variable): bool|string
{
    if (isset($variables[$variable])) return echoVarOrJson($variables[$variable]);
    elseif (isset($variable)) return echoVarOrJson($variable);
    else return false;
}
/**
 * @param array $views
 * @param bool|null $vars
 * @return void
 */
function views(array $views, bool|null $vars = null): void
{
    foreach ($views as $view) $vars ? view($view, $vars) : view($view);
}
/**
 * @param $any
 * @return mixed
 */
function urlencoded_recursive($any): mixed
{
    if (is_string($any)) {
        return decodeVulnerables(str_replace("`","'",str_replace('"',"'",$any)));
    } elseif (is_array($any)) {
        $result = [];
        foreach ($any as $key => $value) {
            $result[decodeVulnerables(str_replace("`","'",str_replace('"',"'",$key)))] = urlencoded_recursive($value);
        }
        return $result;
    } elseif (is_object($any)) {
        $result = new stdClass();
        foreach ($any as $key => $value) {
            $result->{decodeVulnerables(str_replace("`","'",str_replace('"',"'",$key)))} = urlencoded_recursive($value);
        }
        return $result;
    } else return $any;
}
/**
 * @param $string
 * @return string
 */
function encodeVulnerables($string): string
{
    $replaces = [
        "'" => "{QUOTE}",
        "\"" => "{DOUBLE-QUOTE}",
        "`" => "{APOSTROPHE}",
        "<" => "{HTML-ARROW-LEFT}",
        ">" => "{HTML-ARROW-RIGHT}"
    ];
    return htmlspecialchars(str_replace(array_keys($replaces), array_values($replaces), $string));
}
function decodeVulnerablesInArray($array)
{
    foreach ($array as $key => $value) $array[$key] = decodeVulnerables($value);
    return $array;
}
function encodeVulnerablesInArray($array)
{
    foreach ($array as $key => $value) $array[$key] = encodeVulnerables($value);
    return $array;
}
/**
 * @param $string
 * @return string
 */
function decodeVulnerables($string): string
{
    $replaces = [
        "'" => "{QUOTE}",
        "\"" => "{DOUBLE-QUOTE}",
        "`" => "{APOSTROPHE}",
        " " => "{HTML-ARROW-LEFT}",
        "  " => "{HTML-ARROW-RIGHT}"
    ];
    foreach ($replaces as $key => $replace) $string = str_replace($replace, $key, $string);
    return $string;
}
/**
 * @param $any
 * @return void
 */
function ddd($any): void
{
    $any = urlencoded_recursive($any);
    $debugString = is_array($any) ? str_replace("\\/", "/", json_encode($any, true)) : json_encode(["status" => "debug/error","message" => $any]);
    echo "<style>";
    echo "@import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');@import url('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');*{margin:0;padding:0}body{font-family:'Roboto',sans-serif;font-style:normal;font-weight:300;font-smoothing:antialiased;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-size:15px;background:#eee}pre.prettyprint{padding:15px !important;margin:10px;border:0 !important;background:#f2f2f2;overflow:auto}.source{white-space:pre;overflow:auto;max-height:400px}code{border:.5px solid #171819;padding:2px;border-radius:1px}input,textarea{display:block;outline:0;border:0}input:focus,textarea:focus{transition:.2s}body{background-color:#fff;font-family:'Open Sans',Helvetica,sans-serif}.container{width:100%;margin:30px auto}#first{width:350px;float:left;margin-left:2%;margin-right:2%}#two{width:100px;float:left;margin-right:2%;margin-left:2%;padding-top:15%}#three{width:100%;float:left;margin-left:1%;margin-top:-12px}.json-viewer{display:inline-block;height:563px;width:100%;color:#656D78;padding:10px 10px 10px 20px;background-color:#232323;border:4px solid #171819;border-radius:0.4em;margin-bottom:3px}.json-viewer::-webkit-scrollbar{width:10px;}.json-viewer::-webkit-scrollbar-thumb{box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);}.json-viewer ul{list-style-type:none;margin:0;margin:0 0 0 1px;border-left:3px dotted #ccc;padding-left:2em}.json-viewer .hide{display:none}.json-viewer ul li .type-string,.json-viewer ul li .type-date{color:#05bcaf}.json-viewer ul li .type-boolean{color:#F6BB42;font-weight:bold}.json-viewer ul li .type-number{color:#e87376}.json-viewer ul li .type-null{color:#EC87C0}.json-viewer a.list-link{color:#656D78;text-decoration:none;position:relative}.json-viewer a.list-link:before{color:#aaa;content:\"\\25BC\";position:absolute;display:inline-block;width:1em;left:-1em}.json-viewer a.list-link.collapsed:before{content:'\\25B6'}.json-viewer a.list-link.empty:before{content:''}.json-viewer .items-ph{color:#aaa;padding:0 1em}.json-viewer .items-ph:hover{text-decoration:underline}";
    echo "</style>";
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">';
    echo '<pre style="display: none; /* debug pre */">'.$debugString.'</pre>';
    echo '<main><div class="container"><h3 class="mb-3">Dump Die Debug</h3><div id="three"><div id="json"></div></div></div></main>';
    echo '<script>JSONViewer=function(){var e=function(){this._dom={},this._dom.container=document.createElement("pre"),this._dom.container.classList.add("json-viewer")};return e.prototype.showJSON=function(e,t,i){t="number"==typeof t?t:-1,i="number"==typeof i?i:-1;var n=this._processInput(e),a=this._walk(n,t,i,0);this._dom.container.innerHTML="",this._dom.container.appendChild(a)},e.prototype.getContainer=function(){return this._dom.container},e.prototype._processInput=function(e){if(e&&"object"==typeof e)return e;throw"Input value is not object or array!"},e.prototype._walk=function(e,t,i,n){var a=document.createDocumentFragment(),s=t>=0&&n>=t,r=i>=0&&n>=i;switch(typeof e){case"object":if(e){var l=Array.isArray(e),d=l?e:Object.keys(e);if(0===n){var o=this._createItemsCount(d.length),c=this._createLink(l?"[":"{");d.length?(c.addEventListener("click",(function(){s||(c.classList.toggle("collapsed"),o.classList.toggle("hide"),this._dom.container.querySelector("ul").classList.toggle("hide"))}).bind(this)),r&&(c.classList.add("collapsed"),o.classList.remove("hide"))):c.classList.add("empty"),c.appendChild(o),a.appendChild(c)}if(d.length&&!s){var p=d.length-1,h=document.createElement("ul");h.setAttribute("data-level",n),h.classList.add("type-"+(l?"array":"object")),d.forEach(function(a,s){var r=l?a:e[a],d=document.createElement("li");if("object"==typeof r){if(!r||r instanceof Date)d.appendChild(document.createTextNode(l?"":a+": ")),d.appendChild(this._createSimple(r||null));else{var o=Array.isArray(r),c=o?r.length:Object.keys(r).length;if(c){var u=("string"==typeof a?a+": ":"")+(o?"[":"{"),f=this._createLink(u),m=this._createItemsCount(c);t>=0&&n+1>=t?d.appendChild(document.createTextNode(u)):(f.appendChild(m),d.appendChild(f)),d.appendChild(this._walk(r,t,i,n+1)),d.appendChild(document.createTextNode(o?"]":"}"));var g=d.querySelector("ul"),y=function(){f.classList.toggle("collapsed"),m.classList.toggle("hide"),g.classList.toggle("hide")};f.addEventListener("click",y),i>=0&&n+1>=i&&y()}else d.appendChild(document.createTextNode(a+": "+(o?"[]":"{}")))}}else l||d.appendChild(document.createTextNode(a+": ")),d.appendChild(this._walk(r,t,i,n+1));s<p&&d.appendChild(document.createTextNode(",")),h.appendChild(d)},this),a.appendChild(h)}else if(d.length&&s){var u=this._createItemsCount(d.length);u.classList.remove("hide"),a.appendChild(u)}if(0===n){if(!d.length){var u=this._createItemsCount(0);u.classList.remove("hide"),a.appendChild(u)}a.appendChild(document.createTextNode(l?"]":"}")),r&&a.querySelector("ul").classList.add("hide")}break}default:a.appendChild(this._createSimple(e))}return a},e.prototype._createSimple=function(e){var t=document.createElement("span"),i=typeof e,n=e;return"string"===i?n=\'"\'+e+\'"\':null===e?(i="null",n="null"):void 0===e?n="undefined":e instanceof Date&&(i="date",n=e.toString()),t.classList.add("type-"+i),t.innerHTML=n,t},e.prototype._createItemsCount=function(e){var t=document.createElement("span");return t.classList.add("items-ph"),t.classList.add("hide"),t.innerHTML=this._getItemsTitle(e),t},e.prototype._createLink=function(e){var t=document.createElement("a");return t.classList.add("list-link"),t.href="javascript:void(0)",t.innerHTML=e||"",t},e.prototype._getItemsTitle=function(e){return e+" "+(e>1||0===e?"items":"item")},e}();</script>';
    echo '<script>let jssss = `'.$debugString.'`; var jsonObj={},jsonViewer=new JSONViewer;document.querySelector("#json").appendChild(jsonViewer.getContainer());var setJSON=function(e){try{jsonObj=JSON.parse(e)}catch(n){alert(n)}};setJSON(jssss),jsonViewer.showJSON(jsonObj);</script>';
    exit();
}
/**
 * @param ...$vars
 * @return array
 */
function wcompact(...$vars): array
{
    $varsArray = [];
    foreach ($vars as $_ => $var) {
        global $$var;
        $varsArray[$var] = $$var;
    }
    return $varsArray;
}
/**
 * @return string
 */
function get_root_path(): string
{
    $filePath = str_replace(getcwd(), "", getcwd());
    $filePathSlashes = explode(DIRECTORY_SEPARATOR, $filePath);
    unset($filePathSlashes[0]);
    return str_repeat(".." . DIRECTORY_SEPARATOR, count($filePathSlashes));
}
/**
 * @param string|null $any
 * @return string
 */
function resource(string|null $any = null): string
{
    $any = ltrim(rtrim($any, "/"), "/");
    $url = (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 1 || $_SERVER["HTTPS"] == "on"))
        ? "https://" . $_SERVER["SERVER_NAME"] . str_replace("index.php", "", $_SERVER["SCRIPT_NAME"])
        : "http://" . $_SERVER["SERVER_NAME"] . str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
    return $any ? $url . $any : $url;
}
/**
 * @param ...$names
 * @return bool|array
 */
function dirForScan(...$names): bool|array
{
    $migrationsPath = get_root_path();
    foreach ($names as $name) $migrationsPath .= $name.DIRECTORY_SEPARATOR;
    $migrations = scanAllDirs($migrationsPath);
    return [$migrationsPath, $migrations];
}
function scanAllDirs($dir): array
{
    $result = [];
    foreach(scandir($dir) as $filename) {
        if ($filename[0] === '.') continue;
        $filePath = $dir . DIRECTORY_SEPARATOR . $filename;
        if (is_dir($filePath)) {
            foreach (scanAllDirs($filePath) as $childFilename) {
                $result[] = $filename . DIRECTORY_SEPARATOR . $childFilename;
            }
        } else {
            $result[] = $filename;
        }
    }
    return $result;
}
/**
 * @param ...$names
 * @return string
 */
function get_path_to_file(...$names): string
{
    $path = get_root_path();
    foreach ($names as $name) $path .= $name.DIRECTORY_SEPARATOR;
    return $path;
}

class PaperWork{
    public static function get(): array
    {
        return encodeVulnerablesInArray($_GET);
    }
    public static function post(): array
    {
        return encodeVulnerablesInArray($_POST);
    }
    public static function server(): array
    {
        return encodeVulnerablesInArray($_SERVER);
    }
    public static function session(): array
    {
        return encodeVulnerablesInArray($_SESSION);
    }
    public static function cookie(): array
    {
        return encodeVulnerablesInArray($_COOKIE);
    }
    public static function vars(): array
    {
        return $__AQUA_VARIABLES ?? [];
    }
    public static function returnVars($vars): array
    {
        return $vars;
    }
}