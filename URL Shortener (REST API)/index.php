<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST');
header('Content-Type: application/json');
require 'vendor/autoload.php';
require_once 'UrlShortener.php';
require_once 'Database.php';
$inputUrl = 'https://www.cannotthekingdomofsalvationtakemehome.ir';
$db = new Database();
?>

        <?php

        if (isset($_GET['key'])) {
            $database = new Database();
            $shortened = htmlspecialchars(strip_tags($_GET['key']));
            $row = $database->existsInDatabaseShortened($shortened);
            $row = !empty($row) ? $row[0] : false;
            if ($row) {
                $hasHttps = (str_contains($row['raw_url'], "https://"));

                echo json_encode([
                    'raw_url' => ($hasHttps ? "" : "https://") . $row['raw_url'],
                    'shortened' => "{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . '?key=' . $row['shortened']
                ]);
            } else {
                echo json_encode([
                    'message' => "no such URL exists"
                ]);
            }
        } else if (file_get_contents('php://input')) {

            $shortener = new UrlShortener(htmlspecialchars(strip_tags(json_decode(file_get_contents('php://input'))->raw_url)));
            $result = $shortener->shorten();
            if ($result != false) {
                echo $result;
            } else {
                echo "invalid URL";
            }
        }

        ?>
