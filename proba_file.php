<?php

$i = 0;
$file_in = SYSTEM_ROOT."changeset_history.sql";
$file_tmp = SYSTEM_ROOT."changeset_history_tmp.sql";
if(!file_exists($file_in));
file_put_contents($file_in, "");
if(!file_exists($file_tmp));
file_put_contents($file_tmp, "");

copy($file_in, $file_tmp);

$handle = opendir ("C:\server\data\www\asupb.local\sql\changesets\\");
while($file = readdir($handle))
{
    if ($file != '.' && $file != '..')
    {
        $func[$i] = $file;    //формируем массив названий файлов
        $i++;
    }
}

natsort ($func);
echo "<b>#Всего ". sizeof($func). " запросов.</b><br><hr>";
for ($q = 0; $q<sizeof($func); $q++)
{
    $file = 'C:\server\data\www\asupb.local\sql\changesets\\'.$func[$q];
    file_put_contents($file_in, "\n\n#". $file,  FILE_APPEND);
    if(isset($_GET["from"])){
    if($func[$q] < "changeset." . $_GET["from"])
        {
            echo "#==============================================================================";
            continue;
        }
    }
    echo "#<b>".$func[$q].". Последнее изменение: ".date("d.m.Y H:i:s.", filemtime($file)).".  Количество:  ".($q+1)."</b><br>\n";

    $aData = file($file, FILE_IGNORE_NEW_LINES);
    $aNewData = array();
    $aTemp = array();
    foreach ($aData as $value)
    {
        $aTemp[] = $value;
        if (strpos($value, ';'))
        {
         $aNewData[] = implode("\r\n", $aTemp);
         $aTemp = array();
        }
    }
    file_put_contents($file_in, "\n\n". implode("\r\n", $aNewData),  FILE_APPEND);

    foreach ($aNewData as  $value)
    {
      echo nl2br($value)."<br>";
    }
    echo "<br>#-----------------------------------------------------------<br>";
}

?>