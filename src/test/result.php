<?php

namespace Ostanin\Recursion\Tests;

require "../Recursion.php";

use Ostanin\Recursion\RecursionClass;

// Читаем файл по строчно. Что бы память не умерла, Вроде они с 7 версии возможны генераторы или с 7.4, уже не помню.
function readTheFile(string $path) {
	$handle = fopen($path, "r");

	while (!feof($handle)) {
		yield trim(fgets($handle));
	}

	fclose($handle);
}

// stdin
echo 'Введите адрес файла для загрузки : ';

$fileCSV = trim(fgets(STDIN));

echo 'Куда сохранить результат? : ';

$fileJson = trim(fgets(STDIN));

/* [
 *   [0] => Item Name,
 *   [1] => Type,
 *   [2] => Parent,
 *   [3] => Relation
 */
$iterator = readTheFile($fileCSV);

// Вызываем класс
$class = new RecursionClass($iterator);
// Создаём json
$json = json_encode($class->getresultTree($class->createArray()), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// сохраняем результат
file_put_contents($fileJson, $json);

echo "Файл сохранен в " . $fileJson . PHP_EOL;
