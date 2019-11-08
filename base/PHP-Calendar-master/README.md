# PHP Canlendar

A simple OOP based Calendar implementaion based on MVC.
PHP 5.3 - free for commercial or private use.

## Getting Started
See index.php how to use it. Copy all your files into your project. No dependencies
needed.

```
<?php
  include './Calendar.php';
  $calender = new \eu\freeplace\php\calendar\Calendar();
  $calender->draw();
?>
```
File List:
 - Calendar.php :: Contoller
 - CalendarTableView.php :: View (Layout)
 - CalendarCalculation.php :: Mathematics
 - Holidays.php :: Model

To add Holidays (Holidays.php) extend the Array
$this->holidays[Month] [Day] [OPTION: 0 = "HolidayName" | 1 = Type]

## Authors

* **Elmar Dott** - *Concept, Architecture, Development* - [enRebaja](https://enRebaja.wordpress.com)

## License

This project is licensed under the Apache License 2.0

## Contributors

Feel free to send a request by e-mail to contribute the project. In the case you
like this project, let me know it by rate it with a star.
