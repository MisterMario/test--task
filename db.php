<?php

// В этом скрипте будет реализован класс для работы с XML БД

class XMLDB {
  // Вместе с текущим коммитом, позволяющим сделать БД расширяемой этот класс становится синглтоном.
  // Для того, чтобы в единый момент времени доступ к БД был в одной точке приложения (как в AJAX PR v1.0).
  // Нужно написать здесь классические методы доступа к БД (как в SQL):
  /*
    - insert
    - select
    - update
    - delete
  */

  public function __construct() {}

  public function select($table_name, $fields, $rows_num = -1) {
    $simple_xml_el = simplexml_load_file("database/".$table_name.".xml");

    if (isset($simple_xml_el->row)) {
      $rows = array();
      $count = count($simple_xml_el);
      if ($rows_num != -1 && $rows_num <= $count) $count = $rows_num;

      // Извлечение нужных полей
      for ($i=0; $i < $count; $i++) {
        $rows[$i] = array();
        foreach($fields as $field) {
          if (isset($simple_xml_el->row[$i]->$field)) {
            $rows[$i][$field] = (string)$simple_xml_el->row[$i]->$field;
          }
        }
      }

      return $rows;
    }
    return null;
  }

  /*
    Тут есть небольшой ньюанс:
    В БД добавятся только те поля, которые укажет разработчик.
    Есть вариант создать одну строку, которая будет шаблоном для остальных и будет определять структуру строк БД.
    Чтобы поля которые нужно оставить пустыми - остались пустыми, а не вовсе отсутствовали
  */
  public function insert($table_name, $fields_and_values) {
    $simple_xml_el = simplexml_load_file("database/".$table_name.".xml");

    if (isset($simple_xml_el->row)) {
      $new_row = $simple_xml_el->addChild("row");
      foreach($fields_and_values as $field => $value) {
        $new_row->addChild($field, $value);
      }
      file_put_contents("database/".$table_name.".xml", $simple_xml_el->asXML());
    }
    return false;
  }
}

?>
