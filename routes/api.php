<?php

require app_path('Features/Facebook/GenerateFacebookConnectUrl/routes.php');
require app_path('Features/Facebook/CreateFacebookChannelOnCallback/routes.php');
require app_path('Features/Facebook/HandleWebhook/routes.php');
require app_path('Features/Facebook/VerifyFacebookWebhook/routes.php');
require app_path('Features/Line/CreateLineChannel/routes.php');
require app_path('Features/Line/UpdateLineChannel/routes.php');
require app_path('Features/Line/HandleLineWebhook/routes.php');
$widgetCreateRoutes = app_path('Features/Widget/CreateAppAdapterChannel/routes.php');
$widgetUpdateRoutes = app_path('Features/Widget/UpdateAppAdapterChannel/routes.php');

if (file_exists($widgetCreateRoutes)) {
    require $widgetCreateRoutes;
}

if (file_exists($widgetUpdateRoutes)) {
    require $widgetUpdateRoutes;
}
