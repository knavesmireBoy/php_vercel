<?php

function dump($arg)
{
    var_dump($arg);
    exit();
}
function html($text)
{
    return $text ? htmlspecialchars($text, ENT_QUOTES, 'UTF-8') : '';
}
function htmlout($text)
{
    echo html($text);
}
function sinatra()
{
    // echo global $frank;
    $art = $GLOBALS['artists'];
    echo $art[6];
}

function doQuery($pdo, $sql, $msg)
{
    try {
        return $pdo->query($sql);
    } catch (PDOException $e) {
        $error = $msg . ' ' . $e->getMessage();
        include 'error.html.php';
        exit();
    }
}

function doPreparedQuery($st, $msg)
{
    try {
        return $st->execute();
    } catch (PDOException $e) {
        $error = $msg . ' ' . $e->getMessage();
        include './error.html.php';
        exit();
    }
}

function isInt($arg)
{
    $int = (int)$arg;
    return is_int($int) && $int;
}

function makeQuery($conn, $sql, $msg)
{
    if ($conn instanceof PDO) {
        $class = 'selector';
    } else {
        $msg = null;
        $class = 'affector';
    }
    $class = ucfirst($class);
    //require_once str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    require_once $_SERVER['PHP_SELF'] . "/klass/$class.php";
    $q = new $class();
    return $q->makeQuery($conn, $sql, $msg);
}

//https://davidwalsh.name/flatten-nested-arrays-php
function array_flatten($array, $return)
{
    for ($x = 0; $x <= count($array); $x++) {
        if (isset($array[$x]) && is_array($array[$x])) {
            $return = array_flatten($array[$x], $return);
        } else {
            if (isset($array[$x])) {
                $return[] = $array[$x];
            }
        }
    }
    return $return;
}

function sorter1($reg, $str)
{
    return function () use ($reg, $str) {
        $matches = [];
        //preg_match('/sort=([a-z]+)/', $_SERVER['QUERY_STRING'], $matches);
        preg_match($reg, $_SERVER['QUERY_STRING'], $matches);
        return isset($matches[1]) ? $matches[1] : $str;
    };
}

function sorter($reg)
{
    return function ($str) use ($reg) {
        return function () use ($reg, $str) {
            $matches = [];
            preg_match($reg, $_SERVER['QUERY_STRING'], $matches);
            return isset($matches[1]) ? $matches[1] : $str;
        };
    };
}


function getQueryStringLength($n = 0)
{
    return function () use ($n) {
        return strlen(substr($_SERVER['QUERY_STRING'], $n));
    };
}

//$getSubStringOffset = fn($n = 0) => fn() => strlen(substr($_SERVER['QUERY_STRING'], $n));

function getNeedle($str, $arr)
{
    $i = array_search($str, $arr);
    if (!empty($arr[$i])) {
        return $i;
    }
    //return 0;
}

function getIndex($str, $arr)
{
    $res = array_keys(preg_grep('/' . $str . '/i', $arr));
    return !empty($res) ? $res[0] : null;
}

$doDesc = function ($str) {
    return $str . ' DESC';
};
$doAsc = function ($str) {
    return $str . ' ASC';
};


function always($arg)
{
    return function () use ($arg) {
        return $arg;
    };
}

function isEqualDefer($a)
{
    return function ($b) use ($a) {
        return $a === $b;
    };
}

function isEqualDeferInvoke($a)
{
    return function ($b) use ($a) {
        return $a() === $b();
    };
}

function supply($a, $b, $always)
{
    return function ($output) use ($a, $b, $always) {
        return function ($str) use ($output, $a, $b, $always) {
            return strlen($str) === 2 ? $always() . $a($output) : $always() . $b($output);
        };
    };
}

//https://stackoverflow.com/questions/4366730/how-do-i-check-if-a-string-contains-a-specific-word
function exists($substr)
{
    return function ($str) use ($substr) {
        return strpos($str, $substr) !== false;
    };
}

function doCallable($var)
{
    return is_callable($var) ? $var() : $var;
}

function noOp()
{
    return function () {};
}

function doCallableDefer($var)
{
    return function () use ($var) {
        return is_callable($var) ? $var() : $var;
    };
}

function existsDefer($substr)
{
    return function ($str) use ($substr) {
        return function () use ($str, $substr) {
            return strpos($str, $substr) !== false;
        };
    };
}

function setLateValue($o)
{
    return function ($k) use ($o) {
        return function ($v) use ($o, $k) {
            $o[$k] = $v;
        };
    };
}

function lateValueInvoke($f)
{
    return function ($k) use ($f) {
        return function ($v) use ($f, $k) {
            $f($k, $v);
        };
    };
}

function lateValueInvokeCookie($k)
{
    return function ($v) use ($k) {
        setcookie($k, $v);
    };
}


function thunk($f)
{
    return function ($k) use ($f) {
        return function ($v) use ($f, $k) {
            return function () use ($f, $k, $v) {
                return $f($k, $v);
            };
        };
    };
}

function setLateObject($k)
{
    return function ($v) use ($k) {
        return function ($o) use ($v, $k) {
            $o[$k] = $v;
        };
    };
}

function getLateObjectDefer($k)
{
    return function ($o) use ($k) {
        return function () use ($o, $k) {
            return $o[$k];
        };
    };
}

function getObjectDefer($o, $k)
{
    return function () use ($o, $k) {
        return $o[$k];
    };
}


function doWhen($pred, $action)
{
    return function ($arg) use ($pred, $action) {
        if ($pred($arg)) {
            return $action($arg);
        }
    };
}

function doWhenNot($pred, $action)
{
    return function ($arg) use ($pred, $action) {
        if (!$pred($arg)) {
            return $action($arg);
        }
    };
}
//$setcookie = lateValueInvoke(setcookie);