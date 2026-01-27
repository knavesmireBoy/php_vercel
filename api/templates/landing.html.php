<?php
include_once "head.html.php"; ?>
<h3>Please select an artist 14:52</h3>
<form name="cds" method="post" action="">
  Name of Artist: <select name="artist">
    <?php
    foreach ($artists as $key => $artist):
      $str = ($key == $current) ? ' selected="selected"' : ''; ?>
      <option value="<?php echo $key; ?> " <?php echo "$str" ?>>
        <?php echo $artist; ?>
      <?php endforeach; ?>
      </option>
  </select>
  <section><input type="submit" name="submit" value="View Cds" />
    <input type="submit" name="submit" value="New Cd" />
    <input type="submit" name="submit" value="Delete Artist" />
  </section>
</form>
<img src="images/012-DECEMBER-1.jpg"/>
<?php
if (isset($notice)) {
  include 'confirm.html.php';
} ?>
</body>

</html>