<?php
ob_start();

include_once ("ApiCaller.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="index.php" method="post">
    <label for="tname">Select your table</label><select name="tname" id="">
        <option value="planets">Planets</option>
        <option value="satellites">Satellites</option>
        <option value="gases">Gases</option>
        <option value="types">Types</option>
    </select>
    <label for="type">Select your output</label>
    <select name="type" id="">
        <option value="xml">XML</option>
        <option value="json">Json</option>
        <option value="csv">CSV</option>
    </select>

    <input type="submit" value="Click"/>
</form>
<pre>
<?php
$apiCaller = new ApiCaller("http://localhost/API_Server/");
if(!isset($_REQUEST['controller'])) $_REQUEST['controller'] = 'base';
if(!isset($_REQUEST['action'])) $_REQUEST['action'] = 'read';

$data = $apiCaller->sendRequest($_REQUEST);

switch($_REQUEST['type']){
    case "xml" : echo htmlentities($data['data']); break;
    case "json": print_r(json_decode($data['data'],true));break;
    case "csv" : echo $data['data']; break;
}
?>
</pre>
</body>
</html>

