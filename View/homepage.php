<?php
require 'includes/header.php';



?>
<!-- this is the view, try to put only simple if's and loops here.
Anything complex should be calculated in the model -->
<section>
    <form>
        <select name="dropdown" id="dropdown">
            <?php foreach ($names as $row): ?>
                <option><?=$row["firstname"]?></option>
            <?php endforeach ?>

            ?>
            //<label for="dropdown">Select</label>
        </select>
       <select name="dropdown" id="dropdown">
            <?php foreach ($rows as $row): ?>
           <option><?=$row["name"]?></option>
           <?php endforeach ?>

            ?>
          //<label for="dropdown">Select</label>
      </select>
      <button type="submit">Submit</button>




</section>

<?php require 'includes/footer.php'?>