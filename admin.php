<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    case 'listCategories':
        listCategories();
        break;
    case 'newCategory':
        newCategory();
        break;
    case 'editCategory':
        editCategory();
        break;
    case 'deleteCategory':
        deleteCategory();
        break;
    case 'listUsers':
        listUsers();
        break;
    case 'editUser':
        editUser();
        break;
    case 'newUser':
        newUser();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    case 'newSubCategory':
        newSubCategory();
        break;
    case 'deleteSubCategory':
        deleteSubCategory();
        break;
    case 'listSubCategories':
        listSubCategories();
        break;
    case 'editSubCategory':
        editSubCategory();
        break;
    default:
        listArticles();
}

/**
 * Авторизация пользователя (админа) -- установка значения в сессию
 */
function login() {

    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    
    if (isset($_POST['login'])) {
        $inputData = array();
        $inputData['username'] = $_POST['username'];
        $inputData['password'] = $_POST['password'];

        // Пользователь получает форму входа: попытка авторизировать пользователя
        
        if ($_POST['username'] == ADMIN_USERNAME 
                && $_POST['password'] == ADMIN_PASSWORD) {

          // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
          $_SESSION['username'] = ADMIN_USERNAME;
          $_SESSION['adminStatus'] = true;
          header( "Location: admin.php");

        }else if (authentification($inputData)==1){
          $_SESSION['username'] = $_POST['username'];
          header( "Location: admin.php");       
        }else if (authentification($inputData)==2){
          $results['errorMessage'] = "Ваша учетная запись дезактивирована";
          require( TEMPLATE_PATH . "/admin/loginForm.php" );      
        } else {

          // Ошибка входа: выводим сообщение об ошибке для пользователя
          $results['errorMessage'] = "Неправильный пароль или логин, попробуйте ещё раз.";
          require( TEMPLATE_PATH . "/admin/loginForm.php" );
        }

    } else {

      // Пользователь еще не получил форму: выводим форму
      require(TEMPLATE_PATH . "/admin/loginForm.php");
    }
}


/**
 * Аутентификация пользователя
 * 
 * @param assoc  ассоциативный массив с полем username и password
 *  (по умолчанию = не используется)
 * @return int 0|1|2  0- если пароль/логин не найдены, 1 - если пользователь деактивирован,
 * 3 - если данные совпадают и учетная запись активна
 */
function authentification($data) {
    $list = User::getList();
    
    foreach ($list['results'] as $user) {
        if ($user->username == $data['username'] &&
               $user->password == $data['password']) {
            if ($user->activityStatus) {
                return 1;
            }
            else return 2;
        }
    }
    return 0;
}


function logout() {
    unset($_SESSION['username'] );
    unset($_SESSION['adminStatus']);
    header( "Location: admin.php" );
}


function newArticle() {
	  
    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";

    if ( isset( $_POST['saveChanges'] ) ) {
        
        //конвертируем значение чекбокса on в число
        if ($_POST['active'] == "on") {
            $_POST['active'] = 1;
        }
        else $_POST['active'] = 0;
        // Пользователь получает форму редактирования статьи: сохраняем новую статью
        $article = new Article();
        $article->storeFormValues($_POST);      
        
        echo 'POST';
        var_dump($_POST);
        echo 'Article-Object';
        var_dump($article);
        
        $article->insert();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {
        
        // Пользователь еще не получил форму редактирования: выводим форму
        $results['article'] = new Article;
        $data = Category::getList();
        $subCategoryData = SubCategory::getList();
        $results['categories'] = $data['results'];
        $results['subCategories'] = $subCategoryData['results'];
        require( TEMPLATE_PATH . "/admin/editArticle.php" );
    }
}


/**
 * Редактирование статьи
 * 
 * @return null
 */
function editArticle() {
	  
    $results = array();
    $results['pageTitle'] = "Edit Article";
    $results['formAction'] = "editArticle";

    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
            header( "Location: admin.php?error=articleNotFound" );
            return;
        }
        
        //конвертируем значение чекбокса on в число
        if ($_POST['active'] == "on") {
            $_POST['active'] = 1;
        }
        else $_POST['active'] = 0;
        
        $article->storeFormValues($_POST);
        $article->update();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['article'] = Article::getById((int)$_GET['articleId']);
        $data = Category::getList();
        $subCategoryData = SubCategory::getList();
        $results['categories'] = $data['results'];
        $results['subCategories'] = $subCategoryData['results'];
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }

}


function deleteArticle() {

    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header( "Location: admin.php?error=articleNotFound" );
        return;
    }

    $article->delete();
    header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
    $results = array();
    
    $data = Article::getList();
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    foreach ($data['results'] as $category) { 
        $results['categories'][$category->id] = $category;
    }
    
    $results['pageTitle'] = "Все статьи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "articleNotFound") 
            $results['errorMessage'] = "Error: Article not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "articleDeleted")  {
            $results['statusMessage'] = "Article deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/listArticles.php" );
}

function listCategories() {
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Article Categories";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
        if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
    }

    require( TEMPLATE_PATH . "/admin/listCategories.php" );
}
	  
	  
function newCategory() {

    $results = array();
    $results['pageTitle'] = "New Article Category";
    $results['formAction'] = "newCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category->storeFormValues( $_POST );
        $category->insert();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function editCategory() {

    $results = array();
    $results['pageTitle'] = "Edit Article Category";
    $results['formAction'] = "editCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the category changes

        if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
          header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
          return;
        }

        $category->storeFormValues( $_POST );
        $category->update();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById( (int)$_GET['categoryId'] );
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function deleteCategory() {

    if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
        header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
        return;
    }

    $articles = Article::getList( 1000000, $category->id );

    if ( $articles['totalRows'] > 0 ) {
        header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
        return;
    }

    $category->delete();
    header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}

        

function listUsers() {
    
    $results = array();
    
    $data = User::getList();
    $results['users'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];

    $results['pageTitle'] = "Все пользователи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "userNotFound") 
            $results['errorMessage'] = "Error: User not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "userDeleted")  {
            $results['statusMessage'] = "User deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/users/listUsers.php" );
}


function editUser() {
	  
    $results = array();
    $results['pageTitle'] = "Edit User";
    $results['formAction'] = "editUser";

    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$user = User::getById( (int)$_POST['id'] ) ) {
            header( "Location: admin.php?error=userNotFound" );
            return;
        }
        
        //конвертируем значение чекбокса on в число
        if ($_POST['activityStatus'] == "on") {
            $_POST['activityStatus'] = 1;
        }
        else $_POST['activityStatus'] = 0;

        $user->storeFormValues( $_POST );
        $user->update();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['user'] = User::getById((int)$_GET['id']);
        $data = User::getList();
        $results['categories'] = $data['results'];
        require(TEMPLATE_PATH . "/admin/users/editUser.php");
    }
}

function newUser() {
    $results = array();
    $results['pageTitle'] = "New User";
    $results['formAction'] = "newUser";

    if ( isset( $_POST['saveChanges'] ) ) {
//            echo "<pre>";
//            print_r($results);
//            print_r($_POST);
//            echo "<pre>";
//            В $_POST данные о статье сохраняются корректно
        //конвертируем значение чекбокса on в число
        if ($_POST['activityStatus'] == "on") {
            $_POST['activityStatus'] = 1;
        }
        else $_POST['activityStatus'] = 0;
        // Пользователь получает форму редактирования статьи: сохраняем новую статью
        $user = new User();
        $user->storeFormValues( $_POST );

        
        $user->insert();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['user'] = new User;
        $data = User::getList();
        $results['users'] = $data['results'];
        require(TEMPLATE_PATH . "/admin/users/editUser.php");
    }
}

function deleteUser() {

    if ( !$user = User::getById( (int)$_GET['id'] ) ) {
        header( "Location: admin.php?error=userNotFound" );
        return;
    }

    $user->delete();
    header( "Location: admin.php?status=userDeleted" );
}

function listSubCategories() {
    $results = array();
    
    $data = SubCategory::getList();
    $results['subCategories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];

    $results['pageTitle'] = "Все подкатегории";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "subCategoryNotFound") 
            $results['errorMessage'] = "Error: subCategory not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "subCategoryDeleted")  {
            $results['statusMessage'] = "subCategory deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/subCategories/listSubCategories.php" );
}

function newSubCategory() {
    $results = array();
    $results['pageTitle'] = "New SubCategory";
    $results['formAction'] = "newSubCategory";

    if ( isset( $_POST['saveChanges'] ) ) {
//            echo "<pre>";
//            print_r($results);
//            print_r($_POST);
//            echo "<pre>";
//            В $_POST данные о статье сохраняются корректно
        //конвертируем значение чекбокса on в число
        if ($_POST['activityStatus'] == "on") {
            $_POST['activityStatus'] = 1;
        }
        else $_POST['activityStatus'] = 0;
        // Пользователь получает форму редактирования статьи: сохраняем новую статью
        $subCategory = new SubCategory();
        $subCategory->storeFormValues( $_POST );

        
        $subCategory->insert();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['subCategory'] = new SubCategory();
        $data = SubCategory::getList();
        $results['subCategories'] = $data['results'];
        require(TEMPLATE_PATH . "/admin/subCategories/editSubCategory.php");
    }
}

function editSubCategory() {
    $results = array();
    $results['pageTitle'] = "Edit SubCategory";
    $results['formAction'] = "editSubCategory";

    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$subCategory = SubCategory::getById( (int)$_POST['id'] ) ) {
            header( "Location: admin.php?error=subCategoryNotFound" );
            return;
        }

        $subCategory->storeFormValues($_POST);
        $subCategory->update();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['subCategory'] = SubCategory::getById((int)$_GET['id']);
        $data = SubCategory::getList();
        $categoryData = Category::getList();
        $results['subCategories'] = $data['results'];
        $results['categories'] = $categoryData['results'];
        require(TEMPLATE_PATH . "/admin/subCategories/editSubCategory.php");
    }
}

function deleteSubCategory() {

    if ( !$subCategory = SubCategory::getById( (int)$_GET['id'] ) ) {
        header( "Location: admin.php?error=subCategoryNotFound" );
        return;
    }

    $subCategory->delete();
    header( "Location: admin.php?status=subCategoryDeleted" );
}
