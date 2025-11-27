<?php

require app_path('Features/Facebook/GenerateFacebookConnectUrl/routes.php');
require app_path('Features/Facebook/CreateFacebookChannelOnCallback/routes.php');
require app_path('Features/Facebook/HandleWebhook/routes.php');
require app_path('Features/Facebook/VerifyFacebookWebhook/routes.php');
require app_path('Features/Facebook/SendFacebookMessage/routes.php');
require app_path('Features/Line/CreateLineChannel/routes.php');
require app_path('Features/Line/UpdateLineChannel/routes.php');
require app_path('Features/Line/HandleLineWebhook/routes.php');
require app_path('Features/Line/SendLineMessage/routes.php');
require app_path('Features/Widget/CreateWidgetChannel/routes.php');
require app_path('Features/Widget/HandleWebhook/routes.php');

