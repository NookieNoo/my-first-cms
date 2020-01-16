<?php

/**
 * Класс для обработки пользователей
 */
class User
{
    // Свойства
    
    /**
    * @var int id  пользователя
    */
    public $id = null;
    
    /**
    * @var string логин пользователя
    */
    public $username = null;

    /**
    * @var string пароль пользователя
    */
    public $password = null;
    
    /**
    * @var int Поле активности пользователя
    */
    public $activityStatus = null;
    
    
    /**
     * Создаст объект пользователя
     * 
     * @param array $data массив значений (столбцов) данных пользователя
     */
    public function __construct($data=array())
    {
      if (isset($data['id'])) {
          $this->id = $data['id'];
      }
        
      if (isset($data['username'])) {
          $this->username = $data['username'];
      }
      
      if (isset($data['password'])) {
          $this->password = $data['password'];
      }
      
      if (isset($data['activityStatus'])) {
          $this->activityStatus = (int) $data['activityStatus'];
      }
    }
    
    /**
    * Возвращаем объект пользователя соответствующий заданному ID пользователя
    *
    * @param int ID пользователя
    * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
    */
    public static function getById($id) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM users WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        
        $row = $st->fetch();
        $conn = null;
        
        if ($row) { 
            return new User($row);
        }
    }
    
    /**
    * Возвращает все (или диапазон) объекты User из базы данных
    *
    * @param int $numRows Количество возвращаемых строк (по умолчанию = 1000000)
    * @param string $order Столбец, по которому выполняется сортировка статей
     *  (по умолчанию = "username DESC")
    * @param string $activityFilter Столбец со статусом активности пользователя
     *  (по умолчанию = не используется)
    * @return Array|false Двух элементный массив: results => массив объектов User;
     *  totalRows => общее количество строк
    */
    public static function getList($numRows=1000000, 
             $order="username DESC", $activityFilter = "") 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

        $sql = "SELECT SQL_CALC_FOUND_ROWS *
                FROM users $activityFilter
                ORDER BY  $order  LIMIT :numRows";
       
        $st = $conn->prepare($sql);

        
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        
        
        $st->execute();
        
        $list = array();
        
        while ($row = $st->fetch()) {
            $user = new User($row);
            $list[] = $user;
        }

        // Получаем общее количество пользователей, которые соответствуют критерию
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
    }
    
    /**
    * Выборка всех логинов и id
    */
    public static function selectUsernamesAndId () {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT username, id FROM users";
        $st = $conn->prepare($sql);
        $st->execute();

        $list = array();
        
        while ($row = $st->fetch()) {
            $list[] = $row;
        }
        
        $conn = null;
        if ($list) { 
            return $list;
        }
    }
    
    
    /**
    * Вставляем текущий объем User в базу данных, устанавливаем его ID.
    */
    public function insert() {

        /* ??
        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt"
                . " to insert an Article object that already has its ID property"
                . " set (to $this->id).", E_USER_ERROR );
        */

        // Вставляем пользователя
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO users (username, password, activityStatus)"
                . " VALUES ( :username, :password, :activityStatus)";
        $st = $conn->prepare ($sql);
        $st->bindValue( ":username", $this->username, PDO::PARAM_STR);
        $st->bindValue( ":password", $this->password, PDO::PARAM_STR);
        $st->bindValue( ":activityStatus", $this->activityStatus, PDO::PARAM_INT);
        $st->execute();
        //$this->id = $conn->lastInsertId();
        $conn = null;
    }
    
    
    /**
    * Обновляем текущий объект пользователя в базе данных
    */
    public function update() {

      // Обновляем пользователя
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE users SET username=:username, password=:password,"
              . " activityStatus=:activityStatus WHERE id = :id";
      
      
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":username", $this->username, PDO::PARAM_STR);
      $st->bindValue( ":password", $this->password, PDO::PARAM_STR);
      $st->bindValue( ":activityStatus", $this->activityStatus, PDO::PARAM_INT); 
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
    
    
    /**
    * Удаляем текущий объект пользователя из базы данных
    */
    public function delete() {

      // Удаляем пользователя
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM users WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
    
    /**
    * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
    *
    * @param assoc Значения записи формы
    */
    public function storeFormValues ($params) {

      // Сохраняем все параметры
      $this->__construct($params);
    }
}
