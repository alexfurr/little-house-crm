<?php

$item_id = '';
$page_title = 'New timeline item';
$button_text = 'Add new timeline item';

$activity_title = '';
$activity_content= '';
$activity_date = date('Y-m-d');

if(isset($_GET['item-id']) )
{
    $item_id = $_GET['item-id'];
    $page_title = 'Edit timeline item';
    $my_activity = lh_queries::get_activity_info($item_id);

    $activity_title = $my_activity->activity_title;
    $activity_content = stripslashes($my_activity->activity_content);
    $activity_date = $my_activity->activity_date;
    $activity_date = new DateTime($activity_date);
    $activity_date = $activity_date->format("Y-m-d");

    $button_text = 'Edit timeline item';


}

$client_id = $_GET['client-id'];
$client_meta = lh_queries::get_client($client_id);
$client_name = $client_meta['name'];

echo '<h1>'.$page_title.'</h1>';
echo '<form action="?page=client-activity-edit&client-id='.$client_id.'&action=edit_timeline_item" method="post" class="ek_form">';

$args = array(
    "type" => "date",
    "name" => "activity_date",
    "value" => $activity_date,
    "ID" => "activity_date",
    "label" => "Item Date",
);

echo ek_forms::form_item($args);


$args = array(
    "type" => "textbox",
    "name" => "activity_title",
    "value" => $activity_title,
    "ID" => "activity_title",
    "label" => "Name",
    "width"     => 300,
);

echo ek_forms::form_item($args);

$args = array(
    "type" => "textarea",
    "name" => "activity_content",
    "value" => $activity_content,
    "ID" => "activity_content",
    "label" => "Content",
    "RTE"   => true,
);

echo ek_forms::form_item($args);

echo '<input type="submit" value="'.$button_text.'" class="button-primary">';

echo '<input type="hidden" value="'.$item_id.'" name="item_id" />';
echo '<input type="hidden" value="'.$client_id.'" name="client_id" />';


echo '</form>';

?>
