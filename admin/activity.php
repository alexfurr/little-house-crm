<?php

$client_id = $_GET['client-id'];
$client_meta = lh_queries::get_client($client_id);
$client_name = $client_meta['name'];
$my_activity = lh_queries::get_client_activity($client_id);

$activity_count = count($my_activity);

echo '<h1>'.$client_name.' : Timeline</h1>';
echo '<a href="options.php?page=client-activity-edit&client-id='.$client_id.'" class="button-primary">Add new timeline item</a>';

foreach ($my_activity as $activity_info)
{
    $activity_date = $activity_info->activity_date;
    $activity_title = $activity_info->activity_title;
    $activity_content = php_utils::convert_text_from_db($activity_info->activity_content);
    $item_id = $activity_info->id;


    $activity_date = new DateTime($activity_date);
    $activity_date_str = $activity_date->format("F jS, Y");
    echo '<div class="timeline_item">';
    echo '<h3>'.$activity_date_str.'</h3>';
    echo '<h4>'.$activity_title.'</h4>';
    echo $activity_content;

    echo '<br/><a href="options.php?page=client-activity-edit&client-id='.$client_id.'&item-id='.$item_id.'">Edit</a>';
    echo '</div>';

}
?>
