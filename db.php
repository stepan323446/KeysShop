<?php
$db = new mysqli(
    DB_AUTH['db_host'],
    DB_AUTH['db_username'],
    DB_AUTH['db_password'],
    DB_AUTH['db_name'],
    DB_AUTH['db_port']
);
session_start();

function db_prepare($query, $params = []) {
    global $db;

    // Prepare the query
    $stmt = $db->prepare($query);
    if ($stmt === false) {
        throw new Exception("Error preparing the query: " . $db->error);
    }

    // Bind parameters if provided
    if ($params) {
        $types = str_repeat('s', count($params));
        
        if (!$stmt->bind_param($types, ...$params)) {
            throw new Exception("Error binding parameters: " . $db->error);
        }
    }
    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception("Error executing the query: " . $stmt->error);
    }
    
    // Get the result
    $results = $stmt->get_result();

    // Return all results as an associative array
    return $results;
}
function db_query($query) {
    global $db;

    // Execute the query
    $query_result = $db->query($query);
    
    // Check if the query was successful
    if ($query_result === false) {
        throw new Exception("Error executing query: " . $db->error);
    }

    // Return the results as an associative array
    return $query_result;
}