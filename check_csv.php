<?php
$file = fopen('ORDENES.csv', 'r');
$header = fgetcsv($file);
fclose($file);

foreach ($header as $index => $name) {
    echo "$index: $name\n";
}
