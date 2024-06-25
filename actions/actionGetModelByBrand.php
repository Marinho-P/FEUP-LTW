<?php
require_once(__DIR__ . '/../database/model.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/brand.class.php');

if (isset($_GET['brandId'])) {
    $DB = getDatabaseConnection();
    $brandId = $_GET['brandId'];

    // Query database to fetch models based on brandId
    $models = Model::getAllModelsWithBrandId($DB, $brandId);

    // Prepare response data as JSON
    $response = json_encode($models);

    // Set appropriate headers for JSON response
    header('Content-Type: application/json');

    // Output JSON response
    echo $response;
} else {
    // If brandId is not provided in the request, return a 400 Bad Request response
    http_response_code(400);
    echo json_encode(array("error" => "Missing brandId parameter"));
}
?>