<?php
echo '<h1>Calendar</h1>';

$calendar = mbm_calendar::draw_calendar();

echo '<h2>Pick your booking slot</h2>';

echo '<div id="calendar_wrap">';
echo $calendar;
echo '</div>';



?>
