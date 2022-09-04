<style>
    html, body {
        height: 100%;
    }

    html {
        display: table;
        margin: auto;
    }

    body {
        display: table-cell;
        vertical-align: middle;
    }

    table {
        font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        font-size: 14px;
        border-collapse: collapse;
        text-align: center;
    }

    input {
        height: 50px;
    }

    th, td:first-child {
        background: #AFCDE7;
        color: white;
        padding: 10px 20px;
    }

    th, td {
        border-style: solid;
        border-width: 0 1px 1px 0;
        border-color: white;
        width: 80px;
    }

    td {
        background: #D8E6F3;
    }

    th:first-child, td:first-child {
        text-align: left;
    }

    img {
        width: 150px;
        height: 200px;
    }

    .btn {
        display: inline-block; /* Строчно-блочный элемент */
        background: #8C959D; /* Серый цвет фона */
        color: #fff; /* Белый цвет текста */
        padding: 1rem 1.5rem; /* Поля вокруг текста */
        text-decoration: none; /* Убираем подчёркивание */
        border-radius: 3px; /* Скругляем уголки */
    }
</style>
<?php

$host = 'localhost';
$db = 'inline_blog';
$user = 'root';
$pass = 'root';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);
try {
    $dbh = new PDO(($dsn), $user, $pass, $opt);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}
?>

<form action="#" method="post">
    <h2>Показать пост по комментарию</h2>
    <br><br>
    <input type="text" name="oof2" placeholder="Введите..." class="inputtext">
    <input type="submit" name="oof3" value="Вывести" class="btn">
</form>

<?php


function oof2($pdo, $oof2)
{
    if (strlen($oof2) >= 3) {
        $stmt22 = $pdo->query("SELECT comments.body, posts.title FROM `comments` 
                               INNER JOIN posts ON comments.postId=posts.id 
                               WHERE comments.body LIKE '%$oof2%';")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table>
            <tr>
                <td>Заголовок</td>
                <td>Текст</td>
            </tr>
            <?php
            foreach ($stmt22 as $k => $v) {
                ?>
                <tr>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo $v['body']; ?></td>
                </tr>
            <?php }
?>
        </table>
        <?php
    } else {
        echo "поиск работает от 3 символов";
    }
}

?>
<form action="#" method="post">
    <input type="submit" name="oof4" value="Добавить записи" class="btn">
</form>
<form action="#" method="post">
    <input type="submit" name="oof5" value="Удалить записи" class="btn">
</form>
<?php
function oof3($pdo)
{
    try {
        $ch_posts = curl_init();
        $ch_comments = curl_init();

        curl_setopt($ch_posts, CURLOPT_URL, "https://jsonplaceholder.typicode.com/posts");
        curl_setopt($ch_comments, CURLOPT_URL, "https://jsonplaceholder.typicode.com/comments");
        curl_setopt($ch_posts, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_comments, CURLOPT_RETURNTRANSFER, true);

        $html_posts = curl_exec($ch_posts);
        $html_comments = curl_exec($ch_comments);

        $dom_posts = new DOMDocument();
        $dom_comments = new DOMDocument();
        @ $dom_posts->loadHTML($html_posts);
        @ $dom_comments->loadHTML($html_comments);
        $h2s_posts = $dom_posts->getElementsByTagName('html');
        $h2s_comments = $dom_comments->getElementsByTagName('html');
        $h2_array_posts = array();
        $h2_array_comments = array();
        foreach ($h2s_posts as $h2_posts) {
            $title_text_posts = $h2_posts->textContent;
            $h2_array_posts[] = $title_text_posts;
        }
        foreach ($h2s_comments as $h2_comments) {
            $title_text_comments = $h2_comments->textContent;
            $h2_array_comments[] = $title_text_comments;
        }

        $array1 = ["{", "}", ":", "[", "]", '"', "userId", "id", "title", "body", " "];
        $array2 = ["{", "}", ":", "[", "]", '"', "postId", "id", "email", "body", "name", " "];

        $prikol = str_replace($array1, "", trim($title_text_posts));
        $prikol1 = str_replace($array2, "", trim($title_text_comments));
        $array1 = explode(",", $prikol);
        $array1 = array_chunk($array1, 4);
        $array2 = explode(",", $prikol1);
        $array2 = array_chunk($array2, 5);
        $lol1 = 0;
        $lol2 = 0;

        foreach ($array2 as $row => $field) {
            $pdo->query("INSERT INTO comments SET
        `postId`= $field[0],
        `id`= $field[1],
        `name`= '$field[2]',
        `email`= '$field[3]',
        `body`= '$field[4]'");
            $lol2++;
        }
        foreach ($array1 as $row => $field) {
            $pdo->query("INSERT INTO posts SET
        `userId`= $field[0],
        `id`= $field[1],
        `title`= '$field[2]',
        `body`= '$field[3]'");
            $lol1++;
        }
        echo 'Вы добавили ' . $lol1 . ' записей и ' . $lol2 . ' комментариев';
    } catch (Exception $e) {
        echo 'Вы уже добавили записи';
    }
}

function oof4($pdo)
{
    $pdo->query("TRUNCATE TABLE `comments`");
    $pdo->query("TRUNCATE TABLE `posts`");
    echo 'записи удалены';
}

?>

</body>
</html>
<?
if (array_key_exists('oof3', $_POST)) {
    oof2($pdo, $_POST['oof2']);
}
if (array_key_exists('oof4', $_POST)) {
    oof3($pdo);
}
if (array_key_exists('oof5', $_POST)) {
    oof4($pdo);
}
?>
