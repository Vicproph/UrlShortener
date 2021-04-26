<?php
require 'vendor/autoload.php';
require_once 'Database.php';

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

class UrlShortener
{
    private $inputUrl;
    private const SHORTENED_URL_LENGTH = 5;

    public function __construct($inputUrl)
    {
        $this->inputUrl = $inputUrl;
    }

    public function shorten()
    {
        if (empty($this->inputUrl))
            return false;
        $database = new Database();
        if ($database->existsInDatabase($this->inputUrl)) {
            $result = $database->getUrl($this->inputUrl)[0];
            return "
            {$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?key={$result['shortened']}";
        } else {
            $key = $this->generateUrl();
            while ($database->existsInDatabaseShortened($key)) {
                $key = $this->generateUrl();
            }
            $query = " INSERT INTO url_shortener 
            SET 
                raw_url = :raw_url,
                shortened = :shortened
 ";
            $stmt = $database->connection->prepare($query);
            $stmt->bindParam('raw_url', $this->inputUrl);
            $stmt->bindParam('shortened', $key);
            if (!$stmt->execute()) {
                echo "Couldn't insert url into database.";
            } else {
                return "
                {$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?key=$key";
            }
        }
    }

    private function generateUrl()
    {
        $stash = 'abcdefghijklmnopqrstuvwxyzABCEDFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $generatedUrl = '';
        for ($i = 0; $i < self::SHORTENED_URL_LENGTH; $i++)
            $generatedUrl .= $stash[rand(0, strlen($stash) - 1)];
        return $generatedUrl;
    }
}
