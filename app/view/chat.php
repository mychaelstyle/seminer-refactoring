<ul>
<?php foreach ($messages as $message) { ?>
    <li><?php echo $message['name']; ?>
    <?php echo $message['message']; ?>
    <?php echo $message['date']; ?></li>
<?php } ?>
</ul>