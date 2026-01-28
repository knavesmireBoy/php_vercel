<h4>
	<?php
	$h4 = $id ? "$artist - $title" : 'Add New CD';
	$h5  = $id ? '' : 'Adding a first cd for an artist will add that artist to the artists table in the database.'; ?>
	<h4><?= $h4; ?></h4>
	<?php if ($h5): ?>
		<h5><?= $h5; ?></h5>
	<?php endif; ?>
	<form name="CDs" method="<?php htmlout($meth); ?>" action="<?php htmlout($action); ?>">
		<?php if ($id) { ?>
			<input type="hidden" name="releaseID" value="<?php htmlout($id); ?>">
			<input type="hidden" name="artistID" value="<?php htmlout($artistID); ?>">
		<?php } ?>
		<table class="enter">
			<tr>
				<td>Artist</td>
				<td><input type="text" name="artist" value="<?php htmlout($artist) ?>"></td>
				<td rowspan="5" valign="bottom">
					<?php foreach ($buttons as $button): ?>
						<input type="submit" name="submit" value="<?php htmlout($button); ?>" />
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td>Title</td>
				<td><input type="text" name="title" value="<?php htmlout($title) ?>"></td>
			</tr>
			<tr>
				<td>Year</td>
				<td><input type="text" name="year" value="<?php htmlout($year) ?>"></td>
			</tr>
			<tr>
				<td>Label</td>
				<td><input type="text" name="label" value="<?php htmlout($label) ?>"></td>
			</tr>
			<tr>
				<td>Tracks</td>
				<td><input type="text" name="tracks" value="<?php htmlout($tracks) ?>"></td>
			</tr>
		</table>
	</form>
	<?php
	include  'form_copies.html.php';
	?>
	</body>

	</html>
