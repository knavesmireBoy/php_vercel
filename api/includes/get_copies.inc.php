<?php
function html($text){  
return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function htmlout($text){  
echo html($text);
}

$sql_copies = "SELECT * FROM cds_bought WHERE cds_bought.releaseID =" . $_REQUEST['releaseID'];
$result_copies = mysqli_query($conn, $sql_copies);
if (!$result_copies) {
 exit("<p>Error performing query5: " . mysqli_error($conn) . "</p>");
}
while ($row = mysqli_fetch_array($result_copies)) {
$copies[$row['cdID']] =  $row['copy'];
//$copies[] = array('id' => $row['cdID'], 'copy' => $row['copy'], 'rel' => $row['releaseID']);
}
?>