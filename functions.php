<?php
function printArray($array)
{

    if(!is_array($array))
    {
        echo 'This is not an array';
    }

    echo '<pre>';
    print_r($array);
    echo '</pre>';


}





?>
