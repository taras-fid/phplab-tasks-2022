<?php
/**
 * Connect to DB
 */

/** @var PDO $pdo */
require_once './pdo_ini.php';

// const variable for pagination logic
const PAGINATION_INDEX =  5;

// variables for filtering logic
$filterLetter = $_GET['filter_by_first_letter'] ?? null;
$filterSort = $_GET['sort'] ?? null;
$filterPage = $_GET['page'] ?? 1;
$filterState = $_GET['filter_by_state'] ?? null;

/**
 * SELECT the list of unique first letters using https://www.w3resource.com/mysql/string-functions/mysql-left-function.php
 * and https://www.w3resource.com/sql/select-statement/queries-with-distinct.php
 * and set the result to $uniqueFirstLetters variable
 */

foreach ($pdo->query('SELECT DISTINCT LEFT(name, 1) AS letter FROM airports') as $letter) {
    $uniqueFirstLetters[] = $letter['letter'];
}
sort($uniqueFirstLetters);

// Filtering
/**
 * Here you need to check $_GET request if it has any filtering
 * and apply filtering by First Airport Name Letter and/or Airport State
 * (see Filtering tasks 1 and 2 below)
 *
 * For filtering by first_letter use LIKE 'A%' in WHERE statement
 * For filtering by state you will need to JOIN states table and check if states.name = A
 * where A - requested filter value
 */

$sql = ' FROM airports JOIN states on airports.state_id = states.id JOIN cities on airports.city_id = cities.id';
if ($filterLetter && $filterState) {
    $sql .= " WHERE airports.name LIKE '$filterLetter%' AND states.name = '$filterState'";
} elseif ($filterLetter) {
    $sql .= " WHERE airports.name LIKE '$filterLetter%'";
} elseif ($filterState) {
    $sql .= " WHERE states.name = '$filterState'";
}

/**
 * Here you need to check $_GET request if it has sorting key
 * and apply sorting
 * (see Sorting task below)
 *
 * For sorting use ORDER BY A
 * where A - requested filter value
 */

if ($filterSort) { // Sorting with ORDER BY
    $sql .= " ORDER BY $filterSort";
}

// Pagination
/**
 * Here you need to check $_GET request if it has pagination key
 * and apply pagination logic
 * (see Pagination task below)
 *
 * For pagination use LIMIT
 * To get the number of all airports matched by filter use COUNT(*) in the SELECT statement with all filters applied
 */

// 1 more select to get count of all resulted rows of sql query
$rows_count = $pdo->query('SELECT COUNT(*)'.$sql)->fetchAll()[0]['COUNT(*)'];

if ($filterPage == 1) { // setting limits of rows for output
    $sql .= ' LIMIT 5';
} else {
    $limitStart = ($filterPage - 1) * PAGINATION_INDEX;
    $sql .= " LIMIT $limitStart, 5";
}

/**
 * @param int $filterPage
 * @param int $rows_count
 * @return array
 */
function pagination( int $filterPage, int $rows_count): array {

    $paginationArr = [];
    $pageAmong = $rows_count / PAGINATION_INDEX + 1;

    if ($pageAmong < $filterPage + PAGINATION_INDEX ) {
        $end = $pageAmong;
    } else {
        $end = $filterPage + PAGINATION_INDEX;
    }
    $href = '';
    if (isset($_GET['filter_by_first_letter'])) {
        $href .= 'filter_by_first_letter=' . $_GET['filter_by_first_letter'] . '&';
    }
    if (isset($_GET['filter_by_state'])) {
        $href .= 'filter_by_state=' . $_GET['filter_by_state'] . '&';
    }
    if (isset($_GET['sort'])) {
        $href .= 'sort=' . $_GET['sort'] . '&';
    }
    if ($filterPage > PAGINATION_INDEX) {
        for ($i = $filterPage - PAGINATION_INDEX; $i < $end; $i++) {
            if ($i == $filterPage) {
                $paginationArr[] = "<li class=\"page-item active\"><a class=\"page-link\" href=\"./index.php?page=$i&$href\">$i</a></li>";
            } else {
                $paginationArr[] = "<li class=\"page-item\"><a class=\"page-link\" href=\"./index.php?page=$i&$href\">$i</a></li>";
            }
        }
    } else {
        for ($i = 1; $i < $end; $i++) {
            if ($i == $filterPage) {
                $paginationArr[] = "<li class=\"page-item active\"><a class=\"page-link\" href=\"./index.php?page=$i&$href\">$i</a></li>";
            } else {
                $paginationArr[] = "<li class=\"page-item\"><a class=\"page-link\" href=\"./index.php?page=$i&$href\">$i</a></li>";
            }
        }
    }

    return $paginationArr;
}

/**
 * Build a SELECT query to DB with all filters / sorting / pagination
 * and set the result to $airports variable
 *
 * For city_name and state_name fields you can use alias https://www.mysqltutorial.org/mysql-alias/
 */
$airports = $pdo->query('SELECT airports.name as name, code, states.name as state_name, cities.name as city_name, address, timezone' . $sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Airports</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<main role="main" class="container">

    <h1 class="mt-5">US Airports</h1>

    <!--
        Filtering task #1
        Replace # in HREF attribute so that link follows to the same page with the filter_by_first_letter key
        i.e. /?filter_by_first_letter=A or /?filter_by_first_letter=B

        Make sure, that the logic below also works:
         - when you apply filter_by_first_letter the page should be equal 1
         - when you apply filter_by_first_letter, than filter_by_state (see Filtering task #2) is not reset
           i.e. if you have filter_by_state set you can additionally use filter_by_first_letter
    -->
    <div class="alert alert-dark">
        Filter by first letter:

        <?php foreach ($uniqueFirstLetters as $letter): ?>
            <a href="./index.php?page=1&filter_by_first_letter=<?php echo $letter . '&';
            if ($filterState) echo 'filter_by_state=' . $filterState;?>"><?= $letter ?></a>
        <?php endforeach; ?>

        <a href="./index.php" class="float-right">Reset all filters</a>
    </div>

    <!--
        Sorting task
        Replace # in HREF so that link follows to the same page with the sort key with the proper sorting value
        i.e. /?sort=name or /?sort=code etc

        Make sure, that the logic below also works:
         - when you apply sorting pagination and filtering are not reset
           i.e. if you already have /?page=2&filter_by_first_letter=A after applying sorting the url should looks like
           /?page=2&filter_by_first_letter=A&sort=name
    -->
    <table class="table">
        <thead>
        <tr>
            <th scope="col"><a href="./index.php?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=airports.name">Name</a></th>
            <th scope="col"><a href="./index.php?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=code">Code</a></th>
            <th scope="col"><a href="./index.php?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=states.name">State</a></th>
            <th scope="col"><a href="./index.php?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=cities.name">City</a></th>
            <th scope="col">Address</th>
            <th scope="col">Timezone</th>
        </tr>
        </thead>
        <tbody>
        <!--
            Filtering task #2
            Replace # in HREF so that link follows to the same page with the filter_by_state key
            i.e. /?filter_by_state=A or /?filter_by_state=B

            Make sure, that the logic below also works:
             - when you apply filter_by_state the page should be equal 1
             - when you apply filter_by_state, than filter_by_first_letter (see Filtering task #1) is not reset
               i.e. if you have filter_by_first_letter set you can additionally use filter_by_state
        -->
        <?php foreach ($airports as $airport): ?>
        <tr>
            <td><?= $airport['name'] ?></td>
            <td><?= $airport['code'] ?></td>
            <td><a href="./index.php?page=1&<?php if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                ?>filter_by_state=<?= $airport['state_name'] ?>"><?= $airport['state_name'] ?></a></td>
            <td><?= $airport['city_name'] ?></td>
            <td><?= $airport['address'] ?></td>
            <td><?= $airport['timezone'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!--
        Pagination task
        Replace HTML below so that it shows real pages dependently on number of airports after all filters applied

        Make sure, that the logic below also works:
         - show 5 airports per page
         - use page key (i.e. /?page=1)
         - when you apply pagination - all filters and sorting are not reset
    -->
    <nav aria-label="Navigation">
        <ul class="pagination justify-content-center">
            <?php foreach (pagination($filterPage, $rows_count) as $paginationBlock): ?>
            <?= $paginationBlock ?>
            <?php endforeach; ?>
        </ul>
    </nav>

</main>
</html>
