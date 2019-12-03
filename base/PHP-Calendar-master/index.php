<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Calendar Example</title>
    </head>
    <body>
        <div id="calendarDiv">
        <?php
        include './Calendar.php';
        $kalender = new \eu\freeplace\php\calendar\Calendar();
        $kalender->draw();
        ?>
        </div>
        <button type="button" id="submitAddImage">CREATE</button>
    
        <script src="../include/jquery.min.js"></script>
        <script src="calendar.js"></script>
    </body>
</html>
