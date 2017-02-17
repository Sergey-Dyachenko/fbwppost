<?php

    function fbwppost_admin() {
        global $acessToken;
        $appid = '';
        $appsecret='';
        $fb='';
        session_start();
        require_once( "src/Facebook/autoload.php");
        $current_uri = home_url( add_query_arg( NULL, NULL ) );
        if (!empty($_POST['fbw_fbap_application_id'])){
            $appid=$_POST['fbw_fbap_application_id'];
            update_option('fbw_fbap_application_id', $appid);
        }

        $appid = (string)$appid;

        if (!empty($_POST['fbw_fbap_application_secret'])){
            $appsecret=$_POST['fbw_fbap_application_secret'];
            update_option('fbw_fbap_application_secret', $appsecret);
        }

        $appsecret = (string)$appsecret;

        if(!(empty($appid)) && !(empty($appsecret))) {

            $fb = new Facebook\Facebook([
                'app_id' => $appid,
                'app_secret' => $appsecret,
                'default_graph_version' => 'v2.5'
            ]);
        }

        if (isset($params['access_token'])) {
            $params['appsecret_proof'] = $this->getAppSecretProof($params['access_token']);
        }

    if (!empty($fb)) {
        $helper = $fb->getRedirectLoginHelper();

        try {

            if (isset($_SESSION['facebook_access_token']))  {
                $acessToken = $_SESSION['facebook_access_token'];


            } else {
                 $acessToken = $helper->getAccessToken();


            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit();
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit();
        }

        if (isset($acessToken)) {
            if (isset($_SESSION['facebook_access_token'])) {
                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);

            } else {
                //logged in
                $_SESSION['facebook_access_token'] = (string)$acessToken;

                $oAth2Client = $fb->getQAuth2Client();
                /*Excanges a short-lived access token for a long-lived on */
                $acessToken = $oAth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);


            }
            try {

                $response = $fb->get('/me');
                $userNode = $response->getGraphUser();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                //when GRaph returns error
                echo 'Graph returned an error: ' . $e->getMessage();
                unset($_SESSION['facebook_access_token']);
                exit;
            } catch (Facebook\Exceptions\FacebokSDKExceptions $e) {
                //When validation fils or oher local issues
                echo 'Facebook SDK returned an error ' . $e->getMessage();
                exit;
            }
            echo '<h3>Вы зашли в facebook как ' . $userNode->getName().'</h3>';
            $helper = $fb->getRedirectLoginHelper();

            $logoutUrl = $helper->getLogoutUrl($_SESSION['facebook_access_token'], $current_uri, '&');

            echo '<a href="' . htmlspecialchars($logoutUrl) . '"> Выход из аккаунта</a>';


            //Now you can redirect to another page and use
            //access token from $_SESSON['facebook_ccess_token']
        } else {
            $permission = ['email']; //optional
            $loginUrl = $helper->getLoginUrl($current_uri, $permission);
            echo '<a href="' . $loginUrl . '">Войти в Facebook!</a>';

        }

        update_option('fbw_fbap_application_token', $acessToken);

    }
    else {
        update_option('fbw_fbap_application_token', '');
    }

        //$test = get_option('fbw_fbap_application_token');
        //echo $test;
        ?>
    <style>
        .display{
            display:none;
        }
        #col-left{
            width:40% !important;
        }
        .div-scroll{
            overflow-y: auto;
            height: 600px;
       }
        #create-app{
            padding-right: 30px;
        }
        .mailto-us{
            /*            float: right;*/

        }
        #col-right{
            padding-top: 0px;
            width: 50% !important;
        }
        .mail-to-us-div{
            margin-top: 20px;
            margin-left: 72px; 
        }
        .label {
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: bold;
            line-height: 1;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }
        .bg-warning {
            background-color: #0073aa;
            color: #ffffff;
            padding: 5px 20px;
            font-size: 14px;
        }

        .bg-warning :hover{
            color:#ffffff;
        }
        span#suc {
            color: green !important;
        }
        span#error {
            color: red !important;
        }
        textarea#textarea-access-tocken {
            display: none;
        }
        
.accessTError 
{

/*background: #ee0101;*/


border: 2px solid #ee0101;
box-shadow: 0px 0px 6px #000;
-moz-box-shadow: 0px 0px 6px #000;
-webkit-box-shadow: 0px 0px 6px #000;
border-radius: 6px;
-moz-border-radius: 6px;
 -webkit-border-radius: 6px;
 
}
        
    </style>

    <div class="wrap nosubsub">
        <h2>Отправка постов в Facebook</h2>
        <br class="clear">

        <div id="col-container">
            
             <div id="col-right">
                 <div class="col-wrap">
                    <h3>Настройки Facebook приложения <br />
                     <a target="_blank" href="https://developers.facebook.com/apps">Как создать Facebook приложение?</a>
                    </h3> 
                    <textarea id="textarea-access-tocken" cols="60" rows="10" class="">EAAFXmt19If8BAAKKEzyeI2q5xO8iD5TRDapbOsZB86mvQ4ZCEiOTNSZADqdRXPU36iWNwYHE7ebyZAuAZBa9jtRwKInOSyQLkxuuz27ZAdTmD2MYz1ZCyjcNbmI2ChNwdLDG1ibusoTIA9DnP29YZCwP5nTjG8hGnAkXG3pR9FZBMQoeVSyEOB3AY9UihmcGZBVlQZD</textarea>
                     <form method="post">
                     <table>
                         <tr>
                            <td style="font-weight: 700;">
                              Facebook Приложения ID:
                            </td>

                             <td><input type="text" size="50px" name="fbw_fbap_application_id" value="<?php echo get_option('fbw_fbap_application_id');?>"/></td>
                         </tr>
                         <tr>
                             <td style="font-weight: 700;">
                                 Facebook Приложения Secret Key:
                             </td>
                             <td><input type="text" size="50px" name="fbw_fbap_application_secret" value="<?php echo get_option('fbw_fbap_application_secret');?>"/></td>
                         </tr>
                         <tr>
                             <td style="font-weight: 700;">

                             </td>
                             <td><input type="submit" size="20px" name="button" value="Внести данные"/></td>
                         </tr>
                     </table>
                     </form>
                 </div>
             </div>    
            
            <div id="col-left">
                <div class="col-wrap">
                    <h3 style="text-align: center">Текущие посты</h3>
                    <img src="<?php echo plugins_url()."/fbwppost/images/loader.gif"; ?>" class="display" id="loader">
                    <span id="suc" ></span>
                    <span id="error"></span>
                    <form action="" method="post">
                        <table class="wp-list-table widefat fixed tags">
                            <thead>

                                <tr>
                                    <th scope="col" id="name" class="manage-column column-name sortable desc" style="width:30%">
                                        <h4>&nbsp Дата постов:</h4>
                                    </th>
                                    <th scope="col" id="name" class="manage-column column-name sortable desc" style="width:50%">
                            <h4>&nbsp Заголовки постов</h4>
                            </th>
                            <th scope="col" id="name" class="manage-column column-name sortable desc" style="width:20%">
                            <h4>Запостить в Facebook</h4>
                            </th>

                            </tr>
                            </thead>
                        </table>
                        <div class="div-scroll">

                            <table class="wp-list-table widefat fixed tags">

                                <tbody id="the-list" data-wp-lists="list:tag">
                                    <?php

                                    $query = array(
                                        'posts_per_page' => "-1",
                                        'post_status' => array('publish'),
                                    );

                                    $posts = query_posts($query);
                                    $c = 0;
                                    foreach ($posts as $post) {
                                        $c++;
                                        if ($c % 2 == 0) {
                                            $num = "alternate";
                                        } else {
                                            $num = "";
                                        }
                                        $post_title = $post->post_title;
                                        $post_date= $post->post_date;
                                        echo'<tr id="tag-2" class="' . $num . '">
                                      <td class="name column-name" style="width:30%">
                                      <strong>
                                       ' . $post_date . '
                                      </strong>  
                                    </td>  
                                      <td class="name column-name" style="width:50%">
                                      <strong>
                                       ' . $post_title . '
                                      </strong>  
                                    </td>
                                    <td class="check-column" style="width:20%" align="center">
                                       <a href="javascript:" id="' . $post->ID . '" class="label bg-warning post">Post</a>
                                    </td>
                                  </tr>';
                                    }
                                    ?>


                                </tbody>
                            </table>
                        </div>

                        <div class="nav-previous alignleft">

                        </div>
<!--                        <div class="nav-next alignright">--><?php //previous_posts_link( 'Newer posts' ); ?><!--</div>-->


                        <script type="text/javascript">
                            jQuery(document).ready(function($) {
                                $("#textarea-access-tocken").focus();
                                $(".post").click(function() {
                                    
                                    $("#suc").text("")
                                    $("#loader").removeClass("display");
                                    var id = this.id;
                                    var data = {
                                        action: 'post_facebook',
                                        id: id,
                                        access_tocken:$("#textarea-access-tocken").val()
                                    };

                                    $.post(ajaxurl, data, function(res) {


                                        if(res == "1")
                                        {
                                         $("#error").fadeOut('fast');
                                         $("#loader").addClass("display");
                                         var successText = "Пост № " +data['id']+ " отправлен в фейсбук ";
                                         $("#suc").text("Пост отправлен в фейсбук").fadeOut("fast").fadeIn();
                                         $("#textarea-access-tocken").removeClass("accessTError");
                                        }else if(res == "2")
                                        {
                                             $("#suc").fadeOut('fast');
                                             $("#loader").addClass("display");
                                             $("#error").text("Не установлена библиотека Curl").fadeOut("fast").fadeIn();
                                             $("#textarea-access-tocken").removeClass("accessTError");
                                        }else if(res == "3")
                                        {
                                            $("#suc").fadeOut('fast');
                                            $("#loader").addClass("display");
                                            $("#error").text("Пожалуйста введите ID и Secret Key").fadeOut("fast").fadeIn();
                                            $("#textarea-access-tocken").addClass("accessTError");
                                         }
                                        else{
                                          $("#suc").fadeOut('fast');
                                          $("#loader").addClass("display");
                                          $("#error").text(res).fadeOut("fast").fadeIn();
                                          $("#textarea-access-tocken").removeClass("accessTError");
                                        }

                                    });
                               
                               });
                            });
                        </script>



                        <br class="clear">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php

    }

?>