<?php
require 'vendor/autoload.php';
require_once 'UrlShortener.php';
require_once 'Database.php';
$inputUrl = 'https://www.cannotthekingdomofsalvationtakemehome.ir';
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Url Shortener</title>
</head>

<body style=" font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; color : white; background-color: #eee">
    <form action="" method="post" style="width: 50vw; height: 40vh; padding: 2rem; border-radius: 4px; background-color: darkgray; margin: 7rem auto; display: flex; flex-direction: column;">
        <label for="" style="margin-bottom: 1rem;">Input Url</label>
        <input type="text" name="inputUrl" id="" placeholder="Enter your Url..." style="padding: 0.5rem;">
        <input type="submit" value="Submit" style=" background-color: cornflowerblue; padding: 0.7rem 1rem; display: inline-block ;  color: white; border-radius: 12px; border: none; outline: none; text-align: center ; margin-top: 0.5rem;">
        <?php
        if (isset($_GET['key'])) {
            $database = new Database();
            $shortened = htmlspecialchars(strip_tags($_GET['key']));
            $row = $database->existsInDatabaseShortened($shortened);
            $row = empty($row) ? false : $row[0];
            if ($row) {
                $hasHttp = (str_contains($row['raw_url'], "https://"));
                header("Location:" . (!$hasHttp ? "https://" : '') . "{$row['raw_url']}");
            } else {
                echo " <div style=' background-color: #db002b;  margin-top:5rem; padding: 2rem; border-radius: 6px; '>
                    No such short URL exists in database
                    </div>";
            }
        }
        if (isset($_POST['inputUrl'])) {
            echo "<div style='background-color: green; border-top-right-radius: 10px ; border-bottom-left-radius: 10px; display: flex; flex-direction: column; align-items: center; margin-top: 8rem;'>";

            echo "<h4> Shortened URL : </h4>";
            echo "<a style='padding-bottom: 2rem; margin-bottom: 1rem;'>";
            $shortener = new UrlShortener(htmlspecialchars(strip_tags($_POST['inputUrl'])));
            $result = $shortener->shorten();
            if ($result != false) {
                echo $result;
            } else {
                echo "invalid URL";
            }
            echo "</a>";
            echo "</div>";
        }
        ?>
    </form>
</body>

</html>*/