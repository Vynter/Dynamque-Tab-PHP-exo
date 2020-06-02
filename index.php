<?php

use App\NumberHelper;
use App\TableHelper;
use App\URLHelper;

define('PER_PAGE', 20);
require 'vendor/autoload.php';
$pdo = new PDO("sqlite:./products.db", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Recherche de ville
$query = "SELECT * from products";
$queryCount = " SELECT count(id) as count from products";
$param = [];
$sortable = ['id', 'name', 'address', 'city', 'price']; 

if (!empty($_GET['q'])) {
    $query .= " where city like :city ";
    $queryCount  .= " where city like :city ";
    $param["city"] = "%" . $_GET['q'] . "%";
}
//organisation "order by"
if (!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)) {
    $direction = $_GET['dir'] ?? 'asc';
    if (!in_array($direction, ['asc', 'desc'])) { 
        $direction = 'asc';
    }
    $query .=  " ORDER BY " . $_GET['sort'] . " $direction";
}

//pagination
$pageActuel = (int) ($_GET['p'] ?? 1);
$offset = ($pageActuel - 1) * PER_PAGE;


$query .= ' LIMIT ' . PER_PAGE . " offset $offset";

$stat =  $pdo->prepare($query);
$stat->execute($param);
$pdt = $stat->fetchAll();

$stat = $pdo->prepare($queryCount);
$stat->execute($param);
$count = (int) $stat->fetch()['count'];

$pages = ceil($count / PER_PAGE); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bien immobiliers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta name="description" content="<?= $pageDescription ?? 'description du site' ?>">
</head>

<body class="p-4">
    <h1>Les biens Immobiliers</h1>
    <form action="" method="" class="mb-4">
        <div class="form-group">
            <input type="text" name="q" class="form-control" placeholder="Recherche par ville"
                value="<?= htmlentities($_GET['q'] ?? null) ?>">
        </div>
        <div class="form-group">
            <button class="btn btn-primary">Recherche</button></div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= TableHelper::sort('id', 'ID', $_GET) ?></th>
                <th><?= TableHelper::sort('name', 'Nom', $_GET) ?></th>
                <th><?= TableHelper::sort('price', 'Prix', $_GET) ?></th>
                <th><?= TableHelper::sort('address', 'Adresse', $_GET) ?></th>
                <th><?= TableHelper::sort('city', 'Ville', $_GET) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pdt as $v) : ?>
            <tr>
                <td><?= $v['id'] ?> </td>
                <td><?= $v['name'] ?></td>
                <td><?= NumberHelper::Price($v['price']) ?></td>
                <td><?= $v['address'] ?></td>
                <td><?= $v['city'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($pages > 1 && $pageActuel < $pages) : ?>
    <a href="?<?= URLHelper::withParam($_GET, "p", $pageActuel + 1)   ?>" class="btn btn-primary">Page suivante</a>
    <?php endif ?>
    <?php if ($pages > 1 && $pageActuel > 1) : ?>
    <a href="?<?= URLHelper::withParam($_GET, "p", $pageActuel - 1)  ?>" class="btn btn-danger">Page précédente</a>

    <?php endif ?>
</body>

</html>