<?php

    if(isset($argv[1])){

        $dbOpencart = mysqli_connect("localhost", "root", "", "", "3306");
        $module_id = $argv[1];

        $getModuleData = mysqli_query($dbOpencart, "SELECT * FROM journal3_module WHERE module_id = " . $module_id);

        $moduleData = [];
        
        while($module = mysqli_fetch_assoc($getModuleData)){

            $moduleData = json_decode($module['module_data']);

        }

        $products = $moduleData->items[0]->filter->products;
        shuffle($products);
        
        $moduleData->items[0]->filter->products = $products;

        $newModuleData = mysqli_escape_string($dbOpencart, json_encode($moduleData));
        
        mysqli_query($dbOpencart, 'UPDATE journal3_module SET module_data = "'.$newModuleData.'" WHERE module_id = ' . $module_id);

    } else {

        echo "No Argument Defined. Exit." . PHP_EOL;

    }

?>