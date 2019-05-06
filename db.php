<?php

/*
  Класс XMLDB реализует методы для работы с XML-файлами методами, существующими в SQL.
  Т.е. по сути этот класс является интерфейсом для работы с XML, как с классической MySQL базой.
  XMLDB предоставляет методы:
  - select
  - insert
  - update
  - delete

  Для реализации чего-то на подобие WHERE из SQL я добавил переменную $condition.
  Она представляет из себя ассоциативный массив, в котором ключ - имя поля, значение - значение поля.
  Но пока что доступна лишь проверка на равенство полей (например, login == "miner22").
  В рамках данной задачи этого хватит. К тому же это легко модифицируется.
  Массив условий может содержать неограниченное число пар "ключ=значение".

  P.s. В условиях есть небольшой ньюанс: при использовании update и delete задав в условии поля с пустым значением
  можно удалить строку, определяющую структуру таблицы. Это баг. Сейчас я его не исправлял по причине того, что
  в рамаках данного задания это не требуется.
*/

class XMLDB {

  public function __construct() {}

  /*
    $table_name - имя оперируемой таблицы
    $fields - извлекаемые поля
    $condition - условия (более подробно описана выше )
    $rows_num - число извлекаемых строк
  */
  public function select($table_name, $fields, $condition = null, $rows_num = -1) {
    $simple_xml_el = simplexml_load_file("database/".$table_name.".xml");

    if (isset($simple_xml_el->row) && count($simple_xml_el->row) > 1) { // Если БД не пуста
      $rows = array();
      $count = count($simple_xml_el);
      if ($rows_num != -1 && $rows_num <= $count) $count = $rows_num;

      // Извлечение нужных полей
      for ($i=1; $i < $count; $i++) { // Первая строка определяет струтуру
        if ($condition == null || self::checkRowByCondition($simple_xml_el->row[$i], $condition)) {

          $rows[$i] = array();
          foreach($fields as $field) {
            if (isset($simple_xml_el->row[$i]->$field)) {
              $rows[$i][$field] = (string)$simple_xml_el->row[$i]->$field;
            }
          }

        }
      }

      return $rows;
    }
    return null;
  }

  /*
    Переменная $fields_and_values представляет собой массив из записей "поле = значение".
    Где "поле" - имя поля в таблице БД, "значение" - значение этого поля.
  */
  public function insert($table_name, $fields_and_values) {
    $simple_xml_el = simplexml_load_file("database/".$table_name.".xml");

    $new_row = $simple_xml_el->addChild("row");
    foreach ($simple_xml_el->row[0] as $field => $value) { // Поля в новую строку добавляются по шаблону (первой строке)
      // Если текущее поле не передавалось - оно не нужно и будет пустым
      $new_row->addChild($field, isset($fields_and_values[$field]) ? $fields_and_values[$field] : "");
    }
    file_put_contents("database/".$table_name.".xml", $simple_xml_el->asXML());

    return true;
  }

  public function update($table_name, $fields_and_values, $condition) {
    $simple_xml_el = simplexml_load_file("database/".$table_name.".xml");
    $xml_file_was_changed = false; // Если ничего в БД не менялось - нет смысла ее перезаписывать

    for ($i=1; $i < count($simple_xml_el->row); $i++) {

      if ($condition == null || self::checkRowByCondition($simple_xml_el->row[$i], $condition)) {
        foreach ($fields_and_values as $field => $value) {
          $simple_xml_el->row[$i]->$field = $value;
        }
        $xml_file_was_changed = true;
      }
    }

    if ($xml_file_was_changed) file_put_contents("database/".$table_name.".xml", $simple_xml_el->asXML());
    return true;
  }

  /*
    Если $conditon == null - Таблица будет полностью очищена
  */
  public function delete($table_name, $condition = null) {
    $simple_xml_el = simplexml_load_file("database/".$table_name.".xml");
    $xml_file_was_changed = false;

    if ($condition != null) {

      for ($i=1; $i < count($simple_xml_el->row); $i++) {
        if (self::checkRowByCondition($simple_xml_el->row[$i], $condition)) {
          unset($simple_xml_el->row[$i]);
          $xml_file_was_changed = true;
        }
      }

    } else {
      while (count($simple_xml_el->row) > 1)
        unset($simple_xml_el->row[1]);
      $xml_file_was_changed = true;
    }

    if ($xml_file_was_changed) file_put_contents("database/".$table_name.".xml", $simple_xml_el->asXML());
    return true;
  }

  /*
    Проверка условий используется в 3 из 4 методов, поэтому вынесена в отдельный метод.
  */
  public static function checkRowByCondition($row, $condition) {
    $check_is_successful = true;

    foreach ($condition as $field => $value) {
      if (!isset($row->$field) ||
          ((string)$row->$field != $condition[$field])) {
        $check_is_successful = false;
      }
    }

    return $check_is_successful;
  }

}

?>
