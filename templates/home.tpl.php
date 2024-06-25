<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$DB = getDatabaseConnection();



function drawCategories(array $category) {
    ?>
        <nav id ="categories">
            <h2>Categories:</h2>
            <ul>
            <?php
            foreach($category as $row){  
                echo "<li><a href='category.php?categoryId=".$row->categoryId."'>".$row->name."</a></li>";
            }
            ?>
            </ul>
        </nav>  
    <?php
}
?>