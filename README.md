# graph-php
PHP library to build JS-less graphs

### Calendar

Build calendar graphs with unlimited chart layers

#### [Month](https://github.com/YGGverse/graph-php/blob/main/src/Calendar/Month.php)

![2 layers example](https://github.com/YGGverse/graph-php/blob/main/media/calendar/example-2-layers.png?raw=true)

##### Live examples

 * [https://94.140.114.241/yggstate](https://94.140.114.241/yggstate)

##### Usage

###### Server-side

```
// Init current month
$calendarMonth = new Yggverse\Graph\Calendar\Month(
  time(),       // optional, custom timestamp
  CAL_GREGORIAN // optional, define the calendar type (https://www.php.net/manual/en/calendar.constants.php)
);

// Let's build some random graphs for each day in this month
foreach ($calendarMonth->getNodes() as $day => $node)
{
  // Register first layer data
  $calendarMonth->addNode(
    $day,          // required, number of day, on this example we are processing all of days in the initiated month
    rand(1, 1000), // required, set some digit value for this day and current layer
    'label 0',     // optional, label text that could be displayed on element over
    'class-name',  // optional, customize output with CSS class
    0              // optional, define chart layer if you want to build multiple charts in single day case
                   // e.g. 1, 2, 3... or some keyword. first layer has 0 index by default.
  );

  // Make second layer for hourly stats in tis day with few variables
  for ($hour = 0; $hour < 24; $hour++)
  {
    // To build independent chart layer, make sure that layer attribute increased, for example to 1
    $calendarMonth->addNode($day, rand(0, 1000), 'my hourly label 1, 'my-class-name-1', 1);
    $calendarMonth->addNode($day, rand(0, 1000), 'my hourly label 2, 'my-class-name-2', 1);
  }
}
```

###### Client-side

 * [CSS](https://github.com/YGGverse/YGGstate/blob/main/src/public/assets/theme/default/css/yggverse/graph/calendar/month.css)
 * [PHP/HTML](https://github.com/YGGverse/YGGstate/blob/main/src/public/index.php)

To make your own implementation, play with:

```
var_dump(
  $calendarMonth->getNodes()
);
```