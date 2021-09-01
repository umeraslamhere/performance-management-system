<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/admin' => [[['_route' => 'admin', '_controller' => 'App\\Controller\\AdminController::index'], null, null, null, false, false, null]],
        '/admin/signup_requests' => [[['_route' => 'signup_requests', '_controller' => 'App\\Controller\\AdminController::signup_requests'], null, null, null, false, false, null]],
        '/admin/users' => [
            [['_route' => 'all_users', '_controller' => 'App\\Controller\\AdminController::all_users'], null, null, null, false, false, null],
            [['_route' => 'users', '_controller' => 'App\\Controller\\UserController::getUsers'], null, null, null, false, false, null],
        ],
        '/admin/teams' => [[['_route' => 'all_teams', '_controller' => 'App\\Controller\\AdminController::all_teams'], null, null, null, false, false, null]],
        '/admin/teams/create' => [[['_route' => 'create_team', '_controller' => 'App\\Controller\\AdminController::create_team'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\SecurityController::register'], null, null, null, false, false, null]],
        '/register-via-link' => [[['_route' => 'app_register_via_link', '_controller' => 'App\\Controller\\SecurityController::registerViaLink'], null, null, null, false, false, null]],
        '/request_reset_password' => [[['_route' => 'app_request_reset_password', '_controller' => 'App\\Controller\\SecurityController::requestResetPassword'], null, null, null, false, false, null]],
        '/set_password' => [[['_route' => 'app_set_password', '_controller' => 'App\\Controller\\SecurityController::registerViaLinkSetPassword'], null, null, null, false, false, null]],
        '/reset_password' => [[['_route' => 'app_reset_password', '_controller' => 'App\\Controller\\SecurityController::resetPassword'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'home', '_controller' => 'App\\Controller\\UserController::home'], null, null, null, false, false, null]],
        '/dashboard' => [[['_route' => 'user_dashboard', '_controller' => 'App\\Controller\\UserController::dashboard'], null, null, null, false, false, null]],
        '/testing_file_uploading' => [[['_route' => 'upload_test', '_controller' => 'App\\Controller\\UserController::temporaryUploadAction'], null, null, null, false, false, null]],
        '/user/get-all-managers' => [[['_route' => 'all_managers', '_controller' => 'App\\Controller\\UserController::getAllManagers'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:102)'
                            .'|router(*:116)'
                            .'|exception(?'
                                .'|(*:136)'
                                .'|\\.css(*:149)'
                            .')'
                        .')'
                        .'|(*:159)'
                    .')'
                .')'
                .'|/request/(?'
                    .'|de(?'
                        .'|cline_request/([^/]++)(*:208)'
                        .'|tails/([^/]++)(*:230)'
                    .')'
                    .'|approve_request/([^/]++)(*:263)'
                    .'|update/([^/]++)(*:286)'
                .')'
                .'|/user/(?'
                    .'|show/([^/]++)(*:317)'
                    .'|update/([^/]++)(*:340)'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        102 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        116 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        136 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        149 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        159 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        208 => [[['_route' => 'decline_request', '_controller' => 'App\\Controller\\AdminController::delete'], ['id'], null, null, false, true, null]],
        230 => [[['_route' => 'details', '_controller' => 'App\\Controller\\AdminController::show'], ['id'], null, null, false, true, null]],
        263 => [[['_route' => 'approve_request', '_controller' => 'App\\Controller\\AdminController::approve_request'], ['id'], null, null, false, true, null]],
        286 => [[['_route' => 'update_request', '_controller' => 'App\\Controller\\AdminController::update_request'], ['id'], null, null, false, true, null]],
        317 => [[['_route' => 'my_profile', '_controller' => 'App\\Controller\\UserController::show'], ['id'], null, null, false, true, null]],
        340 => [
            [['_route' => 'update_user', '_controller' => 'App\\Controller\\UserController::update'], ['id'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
