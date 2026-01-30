<?php
include_once "head.html.php";

?>
<h3>Please select an artist</h3>
<form name="cds" method="post" action="">
  <label for="artist">Name of Artist:</label><select name="artist" id="artist">
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
<?php
if (isset($notice)) {
  include 'confirm.html.php';
} ?>

<user-card name="Sarah Chen" role="Full Stack Developer"></user-card>
<script>
class UserCard extends HTMLElement {
  constructor() {
    super();
    const shadow = this.attachShadow({mode: 'open'});
    shadow.innerHTML = `
      <style>
        :host {
          display: block;
          padding: 20px;
          border-radius: 12px;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          font-family: 'Inter', sans-serif;
        }
      </style>
      <div class="card">
        <h3>${this.getAttribute('name')}</h3>
        <p>${this.getAttribute('role')}</p>
      </div>
    `;
  }
}
customElements.define('user-card', UserCard);
</script>
</body>

</html>