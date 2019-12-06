<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Calendar Example</title>
    </head>
    <body>
        <?php
        include './Calendar.php';
        $kalender = new \eu\freeplace\php\calendar\Calendar();
        $kalender->draw();
        ?>
    </body>
</html>
