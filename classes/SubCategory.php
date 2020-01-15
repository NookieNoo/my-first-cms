<?php

/**
 * Класс для обработки подкатегорий статей
 */

class SubCategory
{
    // Свойства

    /**
    * @var int ID подкатегории из базы данных
    */
    public $id = null;

    /**
    * @var string Название подкатегории
    */
    public $name = null;

    /**
    * @var int Внешний ключ, ссылка на категию
    */
    public $category_id = null;
 
    
    /**
    * Устанавливаем свойства объекта с использованием значений в передаваемом массиве
    *
    * @param assoc Значения свойств
    */
    
    public function __construct($data=array()) {
      if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if ( isset( $data['name'] ) ) $this->name = $data['name'];
      if ( isset( $data['category_id'] ) ) $this->category_id = $data['category_id'];
    }
    
    /**
    * Устанавливаем свойства объекта с использованием значений из формы редактирования
    *
    * @param assoc Значения из формы редактирования
    */

    public function storeFormValues ($params) {
      $this->__construct($params);
    }
    
    /**
    * Возвращаем объект SubCategory, соответствующий заданному ID
    *
    * @param int ID подкатегории
    * @return SubCategory|false Объект SubCategory object или false, если запись
    *  не была найдена или в случае другой ошибки
    */

    public static function getById($id) 
    {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM subCategories WHERE id = :id";
        $st = $conn->prepare( $sql );
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) {
            return new SubCategory($row);
        }
    }
    
    /**
    * Возвращает название Category, соответствующий заданному subCategory_id
    *
    * @param int ID подкатегории
    * @return string|false название Category, соответствующий заданному subCategory_id
    *  false - если не было найдено или в случае другой ошибки
    */
    
    public static function getCategoryNameBySubCategoryId($id) {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT name "
                . "FROM categories " 
                . "WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        list($name) = $row;
        $conn = null;
        if ($name) {
            return $name;
        }
    }
    
    public static function getCategoryIDBySubCategoryId($id) {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT category_id "
                . "FROM subCategories " 
                . "WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        list($category_id) = $row;
        $conn = null;
        if ($category_id) {
            return $category_id;
        }
    }


    /**
    * Возвращаем все (или диапазон) объектов SubCategory из базы данных
    *
    * @param int Optional Количество возвращаемых строк (по умолчаниюt = all)
    * @param string Optional Столбец, по которому сортируются подкатегории(по умолчанию = "name ASC")
    * @return Array|false Двух элементный массив: results => массив с объектами
    *  SubCategory; totalRows => общее количество подкатегорий
    */
    public static function getList($numRows=1000000, $order="name ASC") 
    {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM subCategories
                ORDER BY $order LIMIT :numRows";

        $st = $conn->prepare( $sql );
        $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
        $st->execute();
        $list = array();

        while ( $row = $st->fetch() ) {
          $subCategory = new subCategory( $row );
          $list[] = $subCategory;
        }

        // Получаем общее количество категорий, которые соответствуют критериям
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query( $sql )->fetch();
        $conn = null;
        return (array (
            "results" => $list,
            "totalRows" => $totalRows[0] ) );
    }
    
    /**
    * Вставляем текущий объект SubCategory в базу данных и устанавливаем его свойство ID.
    */

    public function insert() {

      // Вставляем подкатегорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "INSERT INTO subCategories (name, category_id) VALUES (:name, :category_id)";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":category_id", $this->category_id, PDO::PARAM_INT );
      $st->execute();
      $this->id = $conn->lastInsertId();
      $conn = null;
    }
    
    /**
    * Обновляем текущий объект SubCategory в базе данных.
    */

    public function update() {

      // Обновляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE subCategories SET name=:name, category_id=:category_id WHERE id = :id";
      $st = $conn->prepare ($sql);
      $st->bindValue(":name", $this->name, PDO::PARAM_STR);
      $st->bindValue(":category_id", $this->category_id, PDO::PARAM_INT);
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
      $conn = null;
    }


    /**
    * Удаляем текущий объект SubCategory из базы данных.
    */

    public function delete() {

      // Удаляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ("DELETE FROM subCategories WHERE id = :id LIMIT 1");
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
      $conn = null;
    } 
}