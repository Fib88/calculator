<?php
require 'includes/header.php';


?>

    <section class="mb-5 mt-5 ml-2">
        <form method="post">
            <select name="dropdown" id="dropdown">
                <option>Customer Name</option>
                <?php foreach ($names as $row): ?>
                    <option><?= $row["firstname"] . " " . $row['lastname'] ?></option>
                <?php endforeach ?>
                <label for="dropdown">Select</label>
            </select>

            <select name="dropdown2" id="dropdown2">
                <option>Product & Price</option>
                <?php foreach ($rows as $row): ?>
                    <option><?= $row["name"] . " €" . $row['price'] / 100 ?></option>
                <?php endforeach ?>

                <label for="dropdown2">Select</label>
            </select>
            <button type="submit">Submit</button>

        </form>
    </section>


<?php

require 'includes/footer.php' ?>