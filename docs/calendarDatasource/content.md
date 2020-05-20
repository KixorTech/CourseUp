Calendar as Datasource
----

Now every time a calendar file is read, it is stored within the Calendar.php class. Since the Calendar class is a singleton, there can only be one instance of the calendar at a given time. The most important thing to note about the calendar is how it stores data and how you can get data out of the calendar while writing code for CourseUp

### How the Calendar stores data

When the Calendar reads a calendar file in the `parseCalendarFile` function, it stores both the total number of sessions and the events for each day separately in an array. Thus, if you wanted to get the raw unparsed text for a day, you can use the `getSession($day)` function to return all the events stored on that session number's day. If you want to get the parsed list for a session, you can use the `getBulletList($session, $currentDay, &$itemsDue)`. Last, if you want to get the html for the session, you can use `getSessionHtml($dayCount, &$currentDay, &$weekCount, &$itemsDue)`.

### itemsDue array

The `$itemsDue` array is an array that is kept track of in the view that contains all the items that would be due in the future. This array gets populated by the Calendar so that the view can accurately display the information. It is important that when the view is done writing everything for that day, all the items that are in the items due array are marked one day closer on the days till due list. We avoid doing this in the Calendar code so making multiple calls to `getBulletList` or `getSession` will not subtract off the due dates.

Here is the simple code that is required in the view:
```
for($j=0; $j<count($itemsDue); $j++) {
   $itemsDue[$j]->daysTillDue--;
}
```

### Helper functions

There are also various other helper functions in the Calendar.php class file. However, these functions are fairly self explanatory and give a good set of tools to start creating views. See the other documentation on more information on how to create views.