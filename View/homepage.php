<?php
require 'includes/header.php';



?>
<!-- this is the view, try to put only simple if's and loops here.
Anything complex should be calculated in the model -->
<section>
    <form method="post">
        <select name="dropdown" id="dropdown">
            <?php foreach ($names as $row): ?>
                <option><?=$row["firstname"]. " ". $row['lastname']?></option>
            <?php endforeach ?>
            <label for="dropdown">Select</label>
        </select>
       <select name="dropdown2" id="dropdown2">
            <?php foreach ($rows as $row): ?>
           <option><?=$row["name"]. " €". $row['price']/100?></option>
           <?php endforeach ?>

           <label for="dropdown2">Select</label>
      </select>
      <button type="submit">Submit</button>
    </form>



</section>

<?php require 'includes/footer.php'?>