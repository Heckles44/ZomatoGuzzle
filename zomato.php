<?php 

if(isset($_REQUEST['location'])){
        require './vendor/autoload.php';

        
        $myClient = new GuzzleHttp\Client([
            'headers' => ['User-Agent'=>'ZomatoApp', 'user-key' => '93a6b94e44720afab279f89636f42cae']
            ]);
        
        // Search City
        $city_url = 'https://developers.zomato.com/api/v2.1/cities?q=' . $_REQUEST['location'];
        $city_response = $myClient->request('GET', $city_url);
        if($city_response->getStatusCode() === 200){
            $city_body = $city_response->getBody();
            $city_arr_body = json_decode($city_body);
            $cityId = $city_arr_body->location_suggestions[0]->id;

            // Search Restauants

            $search_url = 'https://developers.zomato.com/api/v2.1/search?entity_id=' . $cityId .'&entity_type=city';
            $search_response = $myClient->request('GET', $search_url);

            if($search_response->getStatusCode() === 200){
                $search_body = $search_response->getBody();
                $search_arr_body = json_decode($search_body);
                $restaurants = $search_arr_body->restaurants;
                
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Zomato API</title>
</head>
<body>
    <form action="">
        <input type="text" name="location">
        <button type="submit">submit</button>        
    </form>    
    <section class="section">
        
        <?php 
        if(!empty($restaurants)){
            foreach($restaurants as $restaurant){
                echo '
                    <article>
                        <h3>' . $restaurant->restaurant->name . '</h3>
                        <a href="' . $restaurant->restaurant->url . '">
                            <img src="' . $restaurant->restaurant->thumb . '" />
                        </a>
                        <h6>' . $restaurant->restaurant->cuisines . '</h6>
                        <p>' . $restaurant->restaurant->location->address . '</p>                        
                    </article>
                ';
            }
        }
        ?>

        </section>

</body>
</html>