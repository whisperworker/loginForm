<?php

function connect() {
    try {
        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DBNAME);
        return $conn;
    } catch (mysqli_sql_exception $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function select($conn) {
    $sql = "SELECT * FROM info";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Выбор количества записей на странцие
function selectMain($conn) {
    $offset = 0;
    if(isset($_GET['offset']) && trim($_GET['offset'])!=''){
        $offset = trim($_GET['offset']);
    }
    $sql = "SELECT * FROM info ORDER BY id DESC LIMIT 3 OFFSET ".$offset*3;
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Выбор определенной записи
function selectArticle($conn) {
    $getId = $_GET['id'];
    $sql = "SELECT * FROM info WHERE id='$getId'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    return false;
}

//Выборка всех тегов
function selectAllTags($conn) {
    $sql = "SELECT DISTINCT(tag) FROM tag";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row['tag'];
        }
    }
    return $a;
}

//Выборка тега для статьи
function selectArticleTag($conn) {
    $getId = $_GET['id'];
    $sql = "SELECT tag, id FROM tag WHERE post='$getId'";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Выборка данных по тегу
function selectPostFromTag($conn) {
    $getTag = $_GET['tag'];
    $sql = "SELECT post FROM tag WHERE tag='$getTag'";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row['post'];
        }
    }

    $sql = "SELECT * FROM info WHERE id in (". join(",", $a).")";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Выборка данных по категории
function selectPostFromCategory($conn) {
    $getId = $_GET['id'];
    $sql = "SELECT * FROM info WHERE category='$getId'";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Выборка информации о категории
function selectCategoryInfo($conn) {
    $getId = $_GET['id'];
    $sql = "SELECT * FROM category WHERE id='$getId'";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }
    return $row;
}

//Выборка всех категорий
function selectAllCategories($conn) {
    $sql = "SELECT * FROM category";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Выборка соотнесенных категорий из таблицы c информацией
function selectCategoriesById($conn) {
    $sql = "SELECT category.category FROM category JOIN info ON category.id = info.category";
    $result = mysqli_query($conn,$sql);

    $a = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $a[] = $row;
        }
    }
    return $a;
}

//Разделение данных по страницам
function paginationCount($conn){
    $sql = "SELECT * FROM info";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($result);
    return ceil($result/3);
}

function updateName($conn, $id, $newName) {
    $sql = "UPDATE info SET name='$newName' WHERE id=".$id;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

function insertData($conn, $title, $descrMin, $description, $image) {
    $sql = "INSERT INTO info (title, descr_min, description, image)
    VALUES ('$title', '$descrMin', '$description', '$image')";

    if (mysqli_query($conn, $sql)) {
        setcookie('bd_create_success', 1, time() + 10);
        header('Location: /admin.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

function deleteArticle($conn, $id) {
    $sql = "DELETE FROM tag WHERE post='$id'";
    mysqli_query($conn, $sql);
    $sql = "DELETE FROM info WHERE id='$id'";
    mysqli_query($conn, $sql);
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

function generateHash($length = 5) {
    $symbol = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789";
    $code = "";
    for($i = 0; $i <= $length; $i++) {
        $code .= $symbol[rand(0, strlen($symbol) - 1)];
    }
    return $code;
}