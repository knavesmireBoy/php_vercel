<p><?php echo $out ?></p>
<form name="copies" method="post" action="?add">
<input type="hidden" name="releaseID" value="<?php htmlout($id);?>"/>
<input type="hidden" name="artist" value="<?php htmlout($artist);?>" />
<input type="hidden" name="title" value="<?php htmlout($title);?>" />
<input type="hidden" name="existing_copies" value="<?php htmlout($count);?>" />
<p>Add<select name="copies" id="copies">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
  </select> Copies <input type="submit" name="submit" value="Add Copy" /></p>
</form>
<?php
if ($count) {
include 'copiesinc.html.php';
} ?>
<a href='.'>Back</a>

