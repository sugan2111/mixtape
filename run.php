#!/usr/bin/php
<?php
require_once 'bootstrap.php';

#Arguments to run the script
[$script, $input_file, $changes_file, $output_file] = $argv;

echo "Decoding Input File.\n";
$data = json_decode(file_get_contents(__DIR__ . "/" . $input_file), true);

echo "Decoding Changes File.\n";
$changes = json_decode(file_get_contents(__DIR__ . "/" . $changes_file), true);

$app = new App($data);
echo "Executing actions.\n";
foreach($changes['actions'] as $action) {
    $app->performAction($action['name'], $action['payload']);
}

echo "Creating Output.\n";
file_put_contents(__DIR__ . "/" . $output_file, json_encode($app->getOutput()));
