<?php

namespace Ostanin\Recursion;

class RecursionClass {

	/**
	 * @var
	 */
	public $iterator;

	/**
	 * @param $iterator
	 */
	public function __construct($iterator) {
		$this->iterator = $iterator;
	}

	/**
	 * @param array $elements
	 * @param $parentId
	 * @return array
	 */
	final public function getresultTree(array $elements, $parentId = null) {

		//Создаём многомерный массив по связи parent - itemName
		$firstArray = $this->recursionFirst($elements, null);

		//Получаем массив для создания дополнительной вложенности и позволяет менять ключ связки.
		$relationArray = array_unique($this->searchValueRecursive("relation", $elements));

		// Данный цикл можно вывести в функцию и замкнуть, тогда можно проверять повторение любого уровня вложенность,
		// но рекурсивный вызов подкатегорий должен справляться с зависимостями.

		foreach ($relationArray as $key => $relation) {

			// получаем массив значений по связке parent - relation
			$result = $this->recursionByElement($elements, null, $relation);

			if (!empty($result)) {
				// Формируем массив путей куда нужно дописать подкатегорий
				$map = [];
				$this->createPathArrayRecursive($relation, 'relation', $firstArray, $map);

				if (!empty($map)) {
					foreach ($map as $path) {
						// Добавляем подкатегорию в главный массив
						$this->setRecursive($firstArray, $path, $result);
					}
				}
				// Удаляем ключ массива для проверки
				// Это что-бы развивать.
				unset($relationArray[$key]);

			}
		}
		// print_r($relationArray);
		return $firstArray;
	}

	/**
	 * @return array
	 * @description Создаем массив из файла.
	 */
	public function createArray() {
		foreach ($this->iterator as $iteration) {

			$data = str_getcsv($iteration, ";");

			if (!empty($data[0])) {
				$array['itemName'] = $data[0];
				$array['type'] = $data[1];
				$array['parent'] = $data[2];
				$array['relation'] = $data[3];

				$result[] = $array;
			}

		}

		return $result;
	}

	/**
	 * @param $array
	 * @param $path
	 * @param $value
	 * @return void
	 * @description Добавление массива в другой многомерный массив по ключу
	 */
	private function setRecursive(&$array, $path, $value) {
		$key = array_shift($path);
		if (empty($path)) {
			$array[$key] = $value;
		} else {
			if (!isset($array[$key]) || !is_array($array[$key])) {
				$array[$key] = array();
			}
			$this->setRecursive($array[$key], $path, $value);
		}
	}

	/**
	 * @param $needle
	 * @param $lastKey
	 * @param $array
	 * @param $result (возврат по ссылке)
	 * @param $currentKey
	 * @return false
	 * @description Создаём массив ключей, чтобы знать куда записывать новый массив в многомерном массиве.
	 */
	private function createPathArrayRecursive($needle, $lastKey, $array, &$result, $currentKey = '') {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->createPathArrayRecursive($needle, $lastKey, $value, $result, $currentKey . $key . '.');
			} else if ($value == $needle && $key == $lastKey) {
				$string = $currentKey . "children";
				$result[] = explode(".", $string);
			}
		}
		return false;
	}

	/**
	 * @param $key
	 * @param array $array
	 * @return array|mixed|null
	 *  @description Получаем массив для создания дополнительной вложенности
	 */
	private function searchValueRecursive($key, array $array) {
		$value = [];
		// вроде замыкания можно использовать с 7 версии, с 7.4 или 7.0, я не помню
		array_walk_recursive($array, function ($item, $kee) use ($key, &$value) {
			if ($kee == $key) {
				if ($item != '') {
					$value[] = $item;
				}
			}
		});
		return count($value) > 1 ? $value : array_pop($value);
	}

	/**
	 * @param $elements
	 * @param $parent
	 * @param $relation
	 * @return array
	 * @description Рекурсия по ключу, просто вызывает не саму себя, а recursionFirst
	 */
	private function recursionByElement($elements, $parent, $relation) {
		$result = [];
		foreach ($elements as $key => $element) {
			if ($element['parent'] == $relation) {
				$children = $this->recursionFirst($elements, $element['itemName']);
				$element['children'] = $children;
				$result[] = $element;
			}
		}
		return $result;
	}

	/**
	 * @param $elements
	 * @param $parent
	 * @return array
	 * @description Самая первая рекурсия по связке parent - name ( самая простая )
	 */
	private function recursionFirst($elements, $parent) {
		$result = [];
		foreach ($elements as $key => $element) {
			if ($element['parent'] == $parent) {
				$children = $this->recursionFirst($elements, $element['itemName']);
				if ($children) {
					$element['children'] = $children;
				}
				if (!$children) {
					$element['children'] = [];
				}
				$result[] = $element;
			}
		}
		return $result;
	}

}
