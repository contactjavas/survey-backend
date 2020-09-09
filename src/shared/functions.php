<?php

use Laminas\Escaper\Escaper;

function getBaseUrl(bool $trailingSlash = false)
{
    $baseUrl = sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['HTTP_HOST']
    );

    if (!$trailingSlash) {
        $baseUrl = rtrim($baseUrl, '/');
    }
}

function getCurrentUrl()
{
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['HTTP_HOST'],
        $_SERVER['REQUEST_URI']
    );
}

function getUrlPath()
{
    return sprintf(
        "%s",
        $_SERVER['REQUEST_URI']
    );
}

function escHtml($output)
{
    $escaper = new Escaper('utf-8');
    return $escaper->escapeHtml($output);
}

function escHtmlAttr($output)
{
    $escaper = new Escaper('utf-8');
    return $escaper->escapeHtmlAttr($output);
}

function escUrl($output)
{
    $escaper = new Escaper('utf-8');
    return $escaper->escapeUrl($output);
}

/**
 * Move the position non-associative array's element
 *
 * @see https://stackoverflow.com/questions/12624153/move-an-array-element-to-a-new-index-in-php/#answer-12624423
 *
 * @param array $array The non-associative array
 * @param int $fromIndex The origin index
 * @param int $toIndex The target index
 */
function moveArrayElement(&$array, $fromIndex, $toIndex)
{
    $out = array_splice($array, $fromIndex, 1);
    array_splice($array, $toIndex, 0, $out);
}

/**
 * Move the position of associative array's element after specific element
 *
 * @see https://stackoverflow.com/questions/21335852/move-an-associative-array-key-within-an-array/#answer-21336407
 *
 * @param array $array The associative array
 * @param int $keyToMove The key of the element to move.
 * @param int $keyTarget The key which the $keyToMove should be inserted after.
 */
function assocArrayMoveAfter(&$array, $keyToMove, $keyTarget)
{
    $elm = $array[$keyToMove];
    unset($array[$keyToMove]);

    $insertPos   = (int) array_search($keyTarget, array_keys($array));
    $arrayBefore = array_slice($array, 0, $insertPos);
    $arrayInsert = [$keyToMove => $elm];
    $arrayAfter  = array_slice($array, $insertPos);

    $array = array_merge(
        $arrayBefore,
        $arrayInsert,
        $arrayAfter
    );
}
