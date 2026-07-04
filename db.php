<?php
try {
    $dsn = "mysql:host=" . DB_AUTH['db_host'] . 
           ";port=" . DB_AUTH['db_port'] . 
           ";dbname=" . DB_AUTH['db_name'] . 
           ";charset=" . DB_AUTH['db_charset'];

    $GLOBALS['pdo'] = new PDO($dsn, DB_AUTH['db_username'], DB_AUTH['db_password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo "Connection db error: " . $e->getMessage();
    die();
}
session_start();

function db_prepare(string $query, array $params = []) {
    global $pdo;

    // Migrate mysqli to pdo
    $pdo_params = [];
    for ($i=0; $i < count($params); $i++) { 
        $pdo_params['param_' . $i] = $params[$i];
        $query = preg_replace('/\?/', ':param_' . $i, $query, 1);
    }

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($pdo_params);
        return $stmt->fetchAll();
    }
    catch (PDOException $e) {
        throw new Exception("PDO Error: " . $e->getMessage());
    }
}
function db_query(string $query) {
    global $pdo;

    try {
        // Execute the query
        $query_result = $pdo->query($query);

        // Return the results as an associative array
        return $query_result;
    }
    catch(PDOException $e) {
        throw new Exception("PDO Error: " . $e->getMessage());
    }
}