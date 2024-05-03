<link rel="stylesheet" href="style.css">

<?
// ПАРСЕР - нужен для того что-бы стырить что-то с другого сайта и то что мы стырели переделать под себя
// например погоду и переделать под себя без использвовония API
// сбор информации

// file_get_content - может спарсить сайт
// $site = file_get_contents('https://itpark32.ru/');
// // echo $site;
// $pattern = '##';
// // сопаоставить функция
// preg_match($pattern, $site, $name_peremennaia);

// file_get_contents - минусы в том что при посещении сайта есть заголовки и при использвоании этой функции заголовки не отправляються и сайт будет думать что это робот и есть вероятность что будет блокировка по ip
// заголовок - подобно тому какой браузер
// заголовок - откуда
// ...


//                      phpQuery
// библиотека для упрощенного парсинга
require 'phpQuery/phpQuery/phpQuery.php';
// убрать высплывающие ошибки
ini_set("display_errors", 0);

$url = 'https://www.kolesa.ru/news';
$html = file_get_contents($url);
// проверка что сайт стырен
// var_dump($html);

// мы не можем воспользоваться методами до иницилизации класса new Class
// :: - это использование статичного метода без иницилизации new Class
// класс имя phpQuery
// метод класса newDocument
// подготавливает сайт для использования библиотеки (внидрил сущьности)
$doc = phpQuery::newDocument($html);
// find = поиск по селектору
// .post-name - имя класса c текстом
// text - вычленить текст
/*
<span class="post-name">
    Аркадий Цареградцев начал новый сезон RDS GP с победы на Moscow Raceway
</span>
*/

// $text = $doc->find('.post-name')->text();
// echo $text;
// echo "<br>";
// echo "<br>";

// Взял все новостные титлы и слил их
// Возьмём более редкое что-то например
// заголовок новостей он не повторяеться

function debag($data)
{
    echo '<pre>' . print_r($data, 1) . '</pre>';
    // если добавить 2 пораметр в print_r то выведит
    // с пробелами print_r($data, 1)
}



// $text = $doc->find('h1')->text();
// echo $text;
// echo "<br>";
// echo "<br>";
// Новости

// займёмся карточками постов новостей
// пройдёмся по каждой карточке
// pq - используем когда только foreach
echo '<div class="posts">';

foreach ($doc->find('.post-list > div.col-md-4.col-sm-6:not(:first-child)') as $index => $post) {
    // pq() равен $() в jQuery или document.querySelector(селектор) выступает как индексация
    // после неё можно использовать методы jQuery
    $post = pq($post);
    // заёдем в нутрь поста и найдём
    // attr() - получить атрибут
    $img_url = $post->find('.post-image')->attr('style');
    $post_name = $post->find('.post-name')->text();
    $post_link = $post->find('.post-list-item')->attr('href');
    // КОПИРУЕТ ПОЛНОСТЬЮ ВЛОЖЕНИЯ но css дописать самому
    // $post_content = $post->find('.post-content')->html();
    $post_content = $post->find('.post-lead');
    // text() не используем т.к. не будем ложить в тег а возьмем с тэгом
    // appendTo() - вставить(куда)
    $post_data = $post->find('.post-meta-item.pull-right');

    echo create_post($img_url, $post_name, $post_link, $post_content, $post_data);
}
// val() - взять значения из полей
// hasClass($class) - проверка
// ...

echo '</div>';

function create_post($img_url, $post_name, $post_link, $post_content, $post_data)
{
    return "
    <div class='card'>
        <a href={$post_link}>
            <h3 class='animation'>
                {$post_name}
            </h3>
        </a>
        <span class='post-image' style='{$img_url}'></span>
        {$post_content}
        {$post_data}
    </div>";
}
