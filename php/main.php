<?php


error_reporting(E_ERROR | E_PARSE);
function validateX($xVal)
{
    return isset($xVal);
}

function validateY($yVal)
{
    if (!isset($yVal))
        return false;

    $numY = str_replace(',', '.', $yVal);
    return is_numeric($numY) && $numY > -3 && $numY < 3;
}

function validateR($rVal)
{
    return isset($rVal);
}

function validateForm($xVal, $yVal, $rVal)
{
    return validateX($xVal) && validateY($yVal) && validateR($rVal);
}

function checkTriangle($xVal, $yVal, $rVal)
{
    return $xVal <= 0 && $yVal >= 0 &&
        $yVal <= $rVal && $xVal * (-1) <= $rVal / 2;
}

function checkRectangle($xVal, $yVal, $rVal)
{
    return $xVal >= 0 && $yVal >= 0 &&
        $xVal <= $rVal && $yVal <= $rVal;
}

function checkCircle($xVal, $yVal, $rVal)
{
    return $xVal <= 0 && $yVal <= 0 &&
        pow($xVal, 2) + pow($yVal, 2) <= pow($rVal / 2, 2);
}

function checkHit($xVal, $yVal, $rVal)
{
    return checkTriangle($xVal, $yVal, $rVal) || checkRectangle($xVal, $yVal, $rVal) ||
        checkCircle($xVal, $yVal, $rVal);
}

function getResultArray($xVal, $yVal, $rVal) {
    $results = array();
    $isValid = validateForm($xVal, $yVal, $rVal);
    $isHit = checkHit($xVal, $yVal, $rVal);
    $currentTime = date('H:i:s', time() + 3600 * 3);
    $executionTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);
    array_push($results, array(
        "validate" => $isValid,
        "x" => $xVal,
        "y" => $yVal,
        "r" => $rVal,
        "currentTime" => $currentTime,
        "execTime" => $executionTime,
        "isHit" => $isHit
    ));
    return $results;
}

function generateTableWithRows( $results) {
    foreach ($results as $elem)
        $html = generateRow($elem);
    $html .= '</table>';
    return $html;
}

function generateRow($elem) {
    $isHit = $elem['isHit'] ? 'Да': 'Нет';
    $elemHtml = $elem["isHit"]? '<tr class="hit-yes">' : '<tr class="hit-no">';
    $elemHtml .= '<td>' . $elem['x'] . '</td>';
    $elemHtml .= '<td>' . $elem['y'] . '</td>';
    $elemHtml .= '<td>' . $elem['r'] . '</td>';
    $elemHtml .= '<td>' . $elem['currentTime'] . '</td>';
    $elemHtml .= '<td>' . $elem['execTime'] . '</td>';
    $elemHtml .= '<td>' . $isHit . '</td>';
    $elemHtml .= '</tr>';

    return $elemHtml;
}


$xVal = $_POST['x'];
$yVal = $_POST['y'];
$rVal = $_POST['r'];
$results = getResultArray($xVal, $yVal, $rVal);
echo generateTableWithRows($results);