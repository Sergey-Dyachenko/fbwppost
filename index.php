<?php
session_start();
require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => '377797629256191',
    'app_secret' => 'e41fc2c4d8d3651cad9d8e8d21812006',
    'default_graph_version' => 'v2.5'
]);

$helper = $fb->getRedirectLoginHelper();
try {
    if (isset($_SESSION['facebook_access_token'])){
        $acessToken = $_SESSION['facebook_access_token'];
    }
    else {
        $acessToken = $helper->getAccessToken();

    }
} catch (Facebook\Exceptions\FacebookResponseException $e){
    echo 'Graph returned an error: '.$e->getMessage();
    exit();
}
catch(Facebook\Exceptions\FacebookSDKException $e){
    echo 'Facebook SDK returned an error: '. $e->getMessage();
    exit();
}

if (isset($acessToken)){
    if (isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else {
        //logged in
        $_SESSION['facebook_access_token'] = (string) $acessToken;

        $oAth2Client = $fb->getQAuth2Client();
        //Excanges a short-lived access token for a long-lived on
        $longLivedAccessToken = $oAth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    try{
        $response = $fb->get('/me');
        $userNode = $response->getGraphUser();
    } catch (Facebook\Exceptions\FacebookResponseException $e){
        //when GRaph returns error
        echo 'Graph returned an error: '.$e->getMessage();
        unset($_SESSION['facebook_access_token']);
        exit;
    } catch (Facebook\Exceptions\FacebokSDKExceptions $e){
        //When validation fils or oher local issues
        echo 'Facebook SDK returned an error '. $e->getMessage();
        exit;
    }
    echo 'Logged in as '. $userNode->getName();
    //Now you can redirect to another page and use
    //access token from $_SESSON['facebook_ccess_token']
 }
    else{
        $permission = ['email']; //optional
        $loginUrl = $helper->getLoginUrl('http://localhost/php_test/index.php', $permission);
        echo '<a href="'. $loginUrl.'">Log in with Facebook!</a>';
    }


?>

<html lang="en"><head>
    <meta charset="">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Jumbotron Template for Bootstrap</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-the#.

           9e.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
                                                                                                  #.

    9
                                                                                                  #.

    9
                                                                                                  #.

    9
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" id="jQStatus">if(typeof(jQuery)!=='undefined'){var event=new CustomEvent('isJQuery',{detail:'yes'});document.dispatchEvent(event);};</script></head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" placeholder="Email" class="form-control" autocomplete="off" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control" autocomplete="off" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
            </form>
        </div><!--/.navbar-collapse -->
    </div>
</nav>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more »</a></p>
    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-default panel-button" href="#" role="button" data-panel="panel1">View details »</a></p>
        </div>
        <div class="col-md-4">
            <h2>Heading</h2>
            <p id="panel1">Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p id = "btn1"><a class="btn btn-default panel-button" href="#" role="button" data-panel="panel2">View details »</a></p>
        </div>
        <div class="col-md-4 test_jq">
            <h2>Heading</h2>
            <p >Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn btn-default panel-button" href="#" role="button" data-panel="panel3">View details »</a></p>
        </div>
    </div>
    <form action="<?php $_SERVER["PHP_SELF"] ?>">
        Куда отправимся:
        <select name="url">
            <option value="http://www.google.com">
                Гугл
            </option>
            <option  value="http://www.yandex.ru">
                Яндекс
            </option>
            <option value="http://www.rambler.ru">
                Рамблер
            </option>
        </select>
        <input type="submit" value="GO!">
    </form>
    <hr>

    <footer>
        <p>© 2016 Company, Inc.</p>
    </footer>
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>



<script>
$(function(){

        $('.panel-buttoon').on('click', function(){
            var panelId = $(this).attr('data-panel');
            console.log(panelId);
            $('#'+panelId).toggle();
        });

});

</script>