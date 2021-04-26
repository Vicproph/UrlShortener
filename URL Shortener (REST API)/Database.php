<?php

class Database
{
    public $connection;
    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host={$_SERVER['SERVER_NAME']};dbname=practice;", 'root', '');
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    public function getUrl($url = '')
    {
        $query = "
            SELECT
                *
            FROM
                url_shortener
            " .
            (($url != '') ? "WHERE raw_url = :url" : '');
        $urls = [];
        $stmt = $this->connection->prepare($query);
        if ($url != '') {
            $stmt->bindParam('url', $url);
        }
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $urls[] = [
                'shortened' => $shortened,
                'raw_url' => $raw_url
            ];
        }
        return $urls;
    }

    public function existsInDatabase($url)
    {
        $result = $this->getUrl($url);
        return count($result) > 0;
    }

    public function existsInDatabaseShortened($short)
    {
        $query = "
            SELECT
                *
            FROM
                url_shortener
             WHERE shortened = :shortened";
        $urls = [];
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam('shortened', $short);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $urls[] = [
                'raw_url' => $raw_url,
                'shortened' => $shortened
            ];
        }
        return $urls;
    }
}
