<h4>Copy Details</h4>
<table class="enter">
<tr>
<th>cdID</th>
<th>No.</th>
<th>Delete</th></tr>
<tr><?php foreach ($copies as $key => $copy): ?>
<td><?php htmlout($key);?></td>
<td><?php htmlout($copy);?></td>
<td><form name="copy_check" method="post" action="?">
    <input type="submit" name="remove" value="X"/>
<input type="hidden" name="key" value="<?php htmlout($key);?>" />
</form></td>
</tr>
<?php endforeach; ?>
</table>
