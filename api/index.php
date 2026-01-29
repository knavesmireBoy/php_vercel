<?php
include_once __DIR__ . '/includes/helpers.inc.php';
function autoloader($className)
{
    $filename = str_replace('\\', '/', $className) . '.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/classes/$filename";
}

spl_autoload_register('autoloader');
$sorter = sorter('/sort=([a-z]+)/');
$orderBy = supply($doAsc, $doDesc, always(' ORDER BY '));
$orderByExtra = supply($doAsc, $doDesc, always(' , '));
$fromFive = getQueryStringLength(5);

//$fromFive = $getSubStringOffset(5);
$q = '';
$alphabetical = new Alphabetical('t');
$chronological = new Chronological('y');
$numerical = new Numerical('r');
$attrs = array('yy' => 'year', 'tt' => 'title',  'rr' => 'tracks', 'aa' => 'artist');
$chronological->setNext($alphabetical)->setNext($numerical);
$ordered = array_map($orderBy, array_values($attrs));
$orderedExtra = array_map($orderByExtra, array_values($attrs));
$aa = array_keys($attrs);
$checkCookie = isEqualDefer($chronological->getType());
$byYear = exists('y');
//$yearIsSet = $byYear($_COOKIE['current']);
$sort = 0;

if (isset($_GET['sort'])) {
    $sorter = $sorter($fromFive());
    $sort = $sorter();
    $i = getIndex($sort, $aa);
    $q = isset($i) ? $ordered[$i]($sort) : null;

    if (!empty($_COOKIE['current'])) {
        $yearIsSet = $byYear($_COOKIE['current']);
        if (!isset($q)) {
            $q = '';
        } else if ($yearIsSet) {
            $q = $ordered[0]($_COOKIE['current']);
            if (!$byYear($sort)) {
                $q .= $orderedExtra[getIndex($sort, $aa)]($sort);
            }
        }
    } else {
        $cookied = $aa[getIndex($_COOKIE['current'], $aa)];
        if (isset($q) && isset($cookied)) {
            $q .= $orderedExtra[getIndex($cookied, $aa)]($cookied);
        }
    }

    include 'includes/db.inc.php';
    $sql = "SELECT * FROM cds, artists";
    $sql .= " WHERE cds.artistid = artists.id AND artists.id = {$_COOKIE['artistid']}  $q";
    $cds = [];
    $result = makeQuery($pdo, $sql, 'Error selecting cds from database:');
    while ($row = $result->fetch()) {
        $cds[] = array(
            'id' => $row['id'],
            'artist' => $row['artist'],
            'title' => $row['title'],
            'year' => $row['year'],
            'label' => $row['label'],
            'tracks' => $row['tracks'],
            'releaseid' => $row['releaseid']
        );
    }
    include __DIR__ . '/../templates/info.html.php';
} else if (!isset($_REQUEST['submit'])) {
    include 'includes/db.inc.php';
    $sql = "SELECT artists.id, artists.artist FROM artists ORDER BY artists.artist";
    $result = makeQuery($pdo, $sql, 'Error selecting artists from database: ');
    $meartist = isset($_GET['artist']) ? $_GET['artist'] : '';
    $current = isset($_GET['id']) ? $_GET['id'] : '';
    while ($row = $result->fetch()) {
        $artists[$row['id']] = $row['artist'];
    }
    include __DIR__ . '/../templates/landing.html.php';
}

if (isset($_POST['submit']) && $_POST['submit'] == "View Cds") {
    setcookie('artistid', $_POST['artist']);
    setcookie('current', 'a');
    include 'includes/db.inc.php';
    $sql = "SELECT * FROM cds, artists";
    $sql .= " WHERE cds.artistid = artists.id AND artists.id = $_POST[artist] ";
    $cds = [];
    $qs = '';
    $result = makeQuery($pdo, $sql, 'Error selecting cds from database:');
    //$result = doQuery($pdo, $sql, 'Error selecting cds from database:');

    while ($row = $result->fetch()) {
        $cds[] = array(
            'id' => $row['artistid'],
            'artist' => $row['artist'],
            'title' => $row['title'],
            'year' => $row['year'],
            'label' => $row['label'],
            'tracks' => $row['tracks'],
            'releaseid' => $row['releaseid']
        );
    }
    include __DIR__ . '/../templates/info.html.php';
} ///////view cds
if (isset($_POST['submit']) && $_POST['submit'] == "Add Copy") {
    $requested = "$_REQUEST[copies]";
    $releaseid = "$_REQUEST[releaseID]";
    include 'includes/db.inc.php';
    $sql = "SELECT copy FROM cds_bought WHERE releaseid = :releaseid";
    $st = $pdo->prepare($sql);
    $st->execute(array('releaseid' => $releaseid));
    $existing = array_flatten($st->fetchAll(PDO::FETCH_NUM), array());
    /*obtain an array of numbers representing a copy of a released cd
    Shoud a copy go missing rather than having gaps [1,4,6] (gaps 2,3,5)
    and simply incrementing from the last number [1,4,6,7] fill in missing numbers [1,2,4,6]*/
    $count = 1;
    while ($requested) {
        $sql = "INSERT INTO cds_bought (releaseid, copy) VALUES ($releaseid,";
        $sql .= "$count)";
        if (!in_array($count, $existing)) {
            makeQuery($pdo, $sql, 'Error inserting a new copy: ');
            $requested -= 1;
        }
        $count += 1;
    }
    header('Location: .');
    exit();
} ///end add copy
if (isset($_GET['submit']) && $_GET['submit'] == "Edit" || isset($_POST['add'])) {
    $title = 'Edit a CD';
    include 'includes/db.inc.php';
    $sql = "SELECT * FROM cds, artists WHERE (cds.artistid = artists.id AND cds.releaseid = $_REQUEST[releaseID])";
    $result = doQuery($pdo, $sql, 'Error selecting cd list from database:');
    $row = $result->fetch();
    $artist = $row['artist'];
    $title = $row['title'];
    $year = $row['year'];
    $label = $row['label'];
    $tracks = $row['tracks'];
    $id = $row['releaseid'];
    $pagetitle = "Shout";
    $status = "submit";
    $artistid = $row['id'];
    $buttons = array(
        "Update",
        "Delete",
        "New Cd"
    );
    $action = "?";
    $meth = "Post"; //////////////
    $sql = "SELECT COUNT(*) FROM cds_bought WHERE cds_bought.releaseid =" . $id;
    $result = doQuery($pdo, $sql, 'Error selecting cd list from database:');
    try {
        $count = $result->fetchColumn();
    } catch (PDOException $e) {
        $error = 'Error getting cd count from database: ' . $e->getMessage();
        include __DIR__ . '/../templates/error.html.php';
        exit();
    }

    if ($count == 0) {
        $out = "<p>There really are no copies of this CD</p>";
    } elseif ($count == 1) {
        $out = "<p>There is $count copy of this CD</p>";
    } else {
        $out = "<p>There are $count copies of this CD</p>";
    }

    $sql = "SELECT * FROM cds_bought WHERE cds_bought.releaseid =" . $id;
    $result = doQuery($pdo, $sql, 'Error getting cd count from database:');
    while ($row = $result->fetch()) {
        $copies[$row['cdid']] = $row['copy'];
    }
    include __DIR__ . '/../templates/edit.html.php';
    exit();
} //edit/add
if (isset($_POST['submit']) && $_POST['submit'] == "Update..") {
    include 'includes/db.inc.php';
    $sql = "UPDATE cds, artists SET ";
    $sql .= "artists.artist = '{$_REQUEST['artist']}', "; //using braces to parse
    //$sql .= "artists.artist = '" . $_REQUEST['artist'] . "', "; //using concatenation
    $sql .= "cds.title = {$_REQUEST['title']}', ";
    $sql .= "cds.year = '" . $_REQUEST['year'] . "', ";
    $sql .= "cds.label = '" . $_REQUEST['label'] . "', ";
    $sql .= "cds.tracks = '" . $_REQUEST['tracks'] . "' ";
    $sql .= "WHERE (artists.id = cds.artistid) ";
    $sql .= "AND (cds.releaseid = $_REQUEST[releaseid])";
    /* Passes query to database */
    $result = doQuery($pdo, $sql, "<p>Error updaing details </p>");
    echo "<p> Successfully Updated " . " rows</p>";
    header('Location: . ');
    exit();
} //update


if (isset($_POST['submit']) && $_POST['submit'] == "Update") {

    include 'includes/db.inc.php';
    $sql = "UPDATE cds SET ";
    $sql .= "title = :title,";
    $sql .= "year = :year,";
    $sql .= "label = :label,";
    $sql .= "tracks = :tracks ";
    $sql .= " FROM artists ";
    $sql .= "WHERE artists.id = cds.artistid ";
    $sql .= "AND cds.releaseid = $_POST[releaseID]";
    $st = $pdo->prepare($sql);
    $st->bindValue(":title", $_POST['title']);
    $st->bindValue(":year", $_POST['year']);
    $st->bindValue(":label", $_POST['label']);
    $st->bindValue(":tracks", $_POST['tracks']);
    $result = doPreparedQuery($st, "<p>Error updating cds table:</p>");

    $sql = "UPDATE artists SET artist = :artist WHERE id = :id";
    $st = $pdo->prepare($sql);
    $st->bindValue(":artist", $_POST['artist']);
    $st->bindValue(":id", $_POST['artistID']);
    $result = doPreparedQuery($st, "<p>Error updating into artists table:</p>");
    header('Location: . ');
    //"<p> Successfully Updated " . " rows</p>"
    exit();
} //update



if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "New Cd") {
    include  'includes/db.inc.php';

    /* NewCD can be requested from two places
    one will deliver the ID of artist from the drop Down Menu
    the other will deliver the VALUE of the artist from a current cd
    A succesful check to see if the value can be CAST to an int will deliver a positive value
    Otherwise it will be cast to zero*/
    $title = 'Add a CD';
    $artist = $_REQUEST['artist'];
    $int = (int)$artist;
    $phrase = isInt($artist) ? 'artists.id = ' : 'artists.artist = ';
    $sql = "SELECT artist FROM artists WHERE $phrase'$artist'";
    $result = doQuery($pdo, $sql, 'Error selecting artist from database');
    $out = '';
    $count = '';
    $artist = $result->fetch()['artist'];
    $title = '';
    $year = '';
    $label = '';
    $tracks = '';
    $id = '';
    $pagetitle = "New CD";
    $action = '';
    $buttons = array(
        "Insert Cd"
    );
    $status = "hidden";
    $meth = "Post";
    include __DIR__ . '/../templates/edit.html.php';
    exit();
}
if (isset($_POST['submit']) && $_POST['submit'] == "Insert Cd") {
    $allfilled = "true"; // sets an $allfilled variable to true. if any fields are empty this will be set to false below
    foreach ($_REQUEST as $name => $value):
        if ($name != 'submit'):
            if (empty($value)): // Checks if there is a value
                $missing = ucfirst($name); // Makes the name of the field Uppercase first letter
                echo "<p>Please Fill out the '$missing' Field</p>"; // Gives missing message to the user
                $allfilled = "false"; // Sets $allfilled variable to false
            endif; // empty check
        endif; // NOT submit
    endforeach;


    if ($allfilled) {
        include 'includes/db.inc.php';
        $artist = $_POST['artist'];
        $sql = "SELECT artists FROM artists WHERE artist = :artist";
        $st = $pdo->prepare($sql);
        $st->bindValue(":artist", $artist);
        $res = doPreparedQuery($st, "<p>Cannot Find Artist:</p>");
        $id = 0;
        if (!$res) {
            $sql = "INSERT INTO artists (artist) VALUES (:artist)";
            $st = $pdo->prepare($sql);
            $st->bindValue(":artist", $_REQUEST['artist']);
            doPreparedQuery($st, "<p>Error inserting into artists table:</p>");
            $id = $pdo->lastInsertId();
        }
        /*$id would be zero if there is an existing artist*/
        if (!$id) {
            $sql = "SELECT id FROM artists WHERE artists.artist = '$artist'";
            $result = doQuery($pdo, $sql, "<p>Error retreiving id:</p>");
            $row = $result->fetch();
            $id = isset($row) ? $row['id'] : null;
        }
        $sql = "INSERT INTO cds (title, year, label, tracks)";
        if($id){
            $sql = rtrim($sql, ')');
            dump($sql);
            $sql .= ", artistid)";
        }
        $sql = "INSERT INTO cds (title, year, label, tracks) VALUES";
        $sql .= "( :title, :year, :label, :tracks)";
        $st = $pdo->prepare($sql);
        $st->bindValue(":title", $_REQUEST['title']);
        $st->bindValue(":year", $_REQUEST['year']);
        $st->bindValue(":label", $_REQUEST['label']);
        $st->bindValue(":tracks", $_REQUEST['tracks']);

        doPreparedQuery($st, "<p>Error inserting values into cds:</p>");
        $id = $pdo->lastInsertId(); //releaseid
        $sql = "INSERT INTO cds_bought (releaseid, copy) VALUES($id, 1)";
        doQuery($pdo, $sql, "<p>Error inserting values into cds_bought:</p>");
    } // true
    header('Location:  . ');
    exit();
} // insert cd

if (isset($_POST['remove']) && $_POST['remove'] == "X") {
    include 'includes/db.inc.php';

    $sql = "DELETE FROM cds_bought WHERE cdid = " . $_POST['key'];
    doQuery($pdo, $sql, 'Error performing deletion:');
    header('Location:  . ');
    exit();
} //delete copy
if (isset($_POST['submit']) && $_POST['submit'] == "Delete") //delete a cd release AND all instances of physical cds
{
    $id = $_POST['releaseID'];
    $nav = $_POST['artistID'];
    $subject = 'cd';
    $notice = "Please note that the cd and a record of its copies will be deleted from the database.";
    include __DIR__ . '/../templates/confirm.html.php';
}
if (isset($_POST['submit']) && $_POST['submit'] == "Delete Artist") //delete artist, cd release AND all instances of physical cds
{
    $id = $_POST['artist'];
    $nav = $id;
    $notice = "Please note that all associated cds will be deleted from the database.";
    $subject = 'artiste';
    include __DIR__ . '/../templates/confirm.html.php';
}

if (isset($_POST['artiste']) && $_POST['submit'] == "destroy") //delete artist, cd release AND all instances of physical cds
{
    include 'includes/db.inc.php';
    $id = intval($_POST['id']);
    /*
    IF NOT FOREIGN KEYS
    $sql = "SELECT  cds.releaseid FROM cds WHERE cds.artistid = $id";
    $result = doQuery($pdo, $sql, "<p>Error retreiving id:</p>");
    $row = $result->fetch();
    $release = $row['releaseid'] ?? null;

    if (isset($release)) {
        $sql = "DELETE FROM cds_bought WHERE cds_bought.releaseid = $release";
        doQuery($pdo, $sql, 'Error performing deletion:');
        $sql = "DELETE FROM cds USING artists WHERE cds.artistid = :id AND cds.releaseid = :release AND artists.id = :id";
        $st = $pdo->prepare($sql);
        $st->bindValue(":id", $id);
        $st->bindValue(":release", $release);
        doPreparedQuery($st, "<p>'Error deleting cd'</p>");
    }
*/
//ALTER TABLE cds ADD FOREIGN KEY (`artistid`) REFERENCES artists(id) ON DELETE CASCADE ON UPDATE CASCADE;

    $sql = "DELETE FROM artists WHERE id = :id";
    $st = $pdo->prepare($sql);
    $st->bindValue(":id", $id);
    doPreparedQuery($st, "<p>Error deleting artist:</p>");
    header('Location:  . ');
    exit();
}
;

if (isset($_POST['cd']) && $_POST['submit'] == "destroy") 
{
    include 'includes/db.inc.php';
    /* IF NOT USING FOREIGN KEY
    $sql = "DELETE cds_bought FROM cds_bought, cds WHERE (cds_bought.releaseid = cds.releaseid) AND ( cds_bought.releaseid = :releaseid)";
    $st = $pdo->prepare($sql);
    $st->bindValue(':releaseid', $_POST['id']);
    $result = doPreparedQuery($st, ' Error deleting copy:');
    */
    //ALTER TABLE cds_bought ADD CONSTRAINT bought_fk FOREIGN KEY (releaseid) REFERENCES cds(releaseid) ON DELETE CASCADE ON UPDATE CASCADE
    $sql = "DELETE FROM cds WHERE cds.releaseid = $_POST[id]";
    $result = doQuery($pdo, $sql, ' Error deleting actual copy:');
    header('Location:  . ');
    exit();
}
