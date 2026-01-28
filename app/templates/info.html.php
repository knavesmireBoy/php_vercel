<?php

include_once "head.html.php"; ?>

<h4>Cds Bought</h4>
<?php
$i = '';
$qs = '';
if (count($cds)): ?>
    <table>
        <?php
        if (!empty($_SERVER['QUERY_STRING'])) {
            $qs = $chronological->handle($sort);
            $qs = empty($qs) ? $chronological->getType() : $qs;
            $i = getIndex($qs, $aa);
            if ($aa[$i]) {
                $aa[$i] = $qs;
                if (exists('y')) {
                  //  setcookie($qs, 'current');
                }
            }
        }
        ?>
        <tr>
            <th><a href="?sort=<?php echo $aa[3]; ?>">Artist</a></th>
            <th><a href="?sort=<?php echo $aa[1]; ?>">Title</a></th>
            <th><a href="?sort=<?php echo $aa[0]; ?>">Year</a></th>
            <th><a href="">Label</a></th>
            <th><a href="?sort=<?php echo $aa[2]; ?>">No. of Tracks</a></th>
            <th><a href="?sort=ee">Edit</a></th>
        </tr>
        <?php

        foreach ($cds as $cd):
            include "list.html.php";
        endforeach;
        ?>
    </table>
    <?php
    $id = html($cds[0]['id']);
    ?>
    <p><a href="?id=<?= $id; ?>">Back</a></p>
<?php
else: ?> <p>There are currently no available cd's for this artist</p>
    <p><a href="?">Back</a></p>
<?php endif; ?>

</body>

</html>