<?php // Display errors, if any.
if (count($errors)) { ?>
<div style="color: red;">
    The following errors occurred:
    <ul>
    <?php foreach ($errors as $error) { ?>
        <li><?=$error;?></li>
    <?php } ?>
    </ul>
</div>
<?php } ?>