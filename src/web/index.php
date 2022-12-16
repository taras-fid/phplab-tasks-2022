<?php

const PAGINATING_INDEX = 5;

require_once './functions.php';

$airports = require './airports.php';

$filterLetter = $_GET['filter_by_first_letter'] ?? null;
$filterSort = $_GET['sort'] ?? null;
$filterPage = $_GET['page'] ?? 1;
$filterState = $_GET['filter_by_state'] ?? null;
$filtersArr = [$filterLetter, $filterState, $filterSort];

// Filtering
/**
 * Here you need to check $_GET request if it has any filtering
 * and apply filtering by First Airport Name Letter and/or Airport State
 * (see Filtering tasks 1 and 2 below)

 * @param string $filterLetter
 * @param array $airports
 * @return array
 */
function firstLetterFilter(string $filterLetter, array $airports): array
{

    $filteredArr = [];

    foreach ($airports as $airport) {
        if (mb_substr($airport['name'], 0, 1) === $filterLetter) {
            $filteredArr[] = $airport;
        }
    }

    return $filteredArr;

}

/**
 * @param string $filterState
 * @param array $airports
 * @return array
 */
function stateFilter(string $filterState, array $airports): array
{

    $filteredArr = [];

    foreach ($airports as $airport) {
        if ($airport['state'] === $filterState) {
            $filteredArr[] = $airport;
        }
    }

    return $filteredArr;

}

if ($filterLetter) {
    $airports = firstLetterFilter($filterLetter, $airports);
}

if ($filterState) {
    $airports = stateFilter($filterState, $airports);
}

// Sorting
/**
 * Here you need to check $_GET request if it has sorting key
 * and apply sorting
 * (see Sorting task below)

 * @param string $sortKey
 * @param array $airports
 * @return array
 */
function codeSorting(string $sortKey, array $airports): array
{

    $inputArray = $airports;
    $keysArray = array_column($airports, $sortKey);
    $ind = array_multisort($keysArray, SORT_ASC, $airports);

    if ($ind) {
        return $airports;
    } else {
        return $inputArray;
    }

}

if ($filterSort) {
    $airports = codeSorting($filterSort, $airports);
}

// Pagination
/**
 * Here you need to check $_GET request if it has pagination key
 * and apply pagination logic
 * (see Pagination task below)

 * @param $filterPage
 * @param array $airports
 * @param array $filters
 * @return array
 */
function pagination($filterPage, array $airports, array $filters): array {

    $filterLetter = $_GET['filter_by_first_letter'] ?? null;
    $filterState = $_GET['filter_by_state'] ?? null;
    $filterSort = $_GET['sort'] ?? null;
    $pageAmong = count($airports) / PAGINATING_INDEX + 1;
    $href = '';
    $paginationArr = [];

    foreach ($filters as $filter) {
        if ($filter != null) {
            if ($filter == $filterLetter) {
                $href .= 'filter_by_first_letter=' . $filter . '&';
            } elseif ($filter == $filterState) {
                $href .= 'filter_by_state=' . $filter . '&';
            } elseif ($filter == $filterSort) {
                $href .= 'sort=' . $filter;
            }
        }
    }

    if ($pageAmong < $filterPage + PAGINATING_INDEX ) {
        $end = $pageAmong;
    } else {
        $end = $filterPage + PAGINATING_INDEX;
    }
    if ($filterPage > PAGINATING_INDEX) {
        for ($i = $filterPage - PAGINATING_INDEX; $i < $end; $i++) {
            if ($i == $filterPage) {
                $paginationArr[] = "<li class=\"page-item active\"><a class=\"page-link\" href=\"/?page=$i&$href\">$i</a></li>";
            } else {
                $paginationArr[] = "<li class=\"page-item\"><a class=\"page-link\" href=\"/?page=$i&$href\">$i</a></li>";
            }
        }
    } else {
        for ($i = 1; $i < $end; $i++) {
            if ($i == $filterPage) {
                $paginationArr[] = "<li class=\"page-item active\"><a class=\"page-link\" href=\"/?page=$i&$href\">$i</a></li>";
            } else {
                $paginationArr[] = "<li class=\"page-item\"><a class=\"page-link\" href=\"/?page=$i&$href\">$i</a></li>";
            }
        }
    }
    return $paginationArr;

}

/**
 * @param $filterPage
 * @param array $airports
 * @return array
 */
function filterPage($filterPage, array $airports): array
{
    $filteredArr = [];
    $startInd = ($filterPage - 1) * PAGINATING_INDEX;
    $endInd = $filterPage * PAGINATING_INDEX - 1;

    for ($i = $startInd; $i <= $endInd; $i++) {
        if (isset($airports[$i]['name'])) {
            $filteredArr[] = $airports[$i];
        }
    }
    return $filteredArr;
}

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

        <?php foreach (getUniqueFirstLetters(require './airports.php') as $letter): ?>
            <a href="/?page=1&filter_by_first_letter=<?php echo $letter . '&';
            if ($filterState) echo 'filter_by_state=' . $filterState;?>"><?= $letter ?></a>
        <?php endforeach; ?>

        <a href="/" class="float-right">Reset all filters</a>
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
            <th scope="col"><a href="/?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=name">Name</a></th>
            <th scope="col"><a href="/?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=code">Code</a></th>
            <th scope="col"><a href="/?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=state">State</a></th>
            <th scope="col"><a href="/?<?php
                if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
                if ($filterState) echo 'filter_by_state=' . $filterState . '&';
                ?>sort=city">City</a></th>
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
        <?php foreach (filterPage($filterPage ,$airports) as $airport): ?>
        <tr>
            <td><?= $airport['name'] ?></td>
            <td><?= $airport['code'] ?></td>
            <td><a href="/?page=1&<?php if ($filterLetter) echo 'filter_by_first_letter=' . $filterLetter . '&';
            ?>filter_by_state=<?= $airport['state'] ?>"><?= $airport['state'] ?></a></td>
            <td><?= $airport['city'] ?></td>
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
            <?php foreach (pagination($filterPage, $airports, $filtersArr) as $paginationBlock): ?>
            <?= $paginationBlock ?>
            <?php endforeach; ?>
        </ul>
    </nav>

</main>
</html>
