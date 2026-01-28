
<form method="post">
    <fieldset><legend>Do you really want to delete this <?= $subject; ?>?
    </legend>
    <input type="hidden" name="id" value="<?= $id; ?>">
    <input type="hidden" name="<?= $subject; ?>" value="<?= $subject; ?>">
    <input type="submit" name="submit" value="destroy"/>
    </fieldset>
</form>
<p><?= $notice; ?></p>
<p><a href="?id=<?= $nav; ?>">Back</a></p>