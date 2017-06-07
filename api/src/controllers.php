<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    $data = [
        "message" => "welcome",
    ];

    return $app->json($data);
})->bind('homepage');

$app->get('/get/services', function (Request $request) use ($app) {
    $sql = "SELECT * FROM `services`";

    $data = $app['db']->fetchAll($sql);

    return $app->json($data);
})->bind('getServices');

$app->get('/get/companies', function (Request $request) use ($app) {
    $sql = "SELECT * FROM `companies`";

    $data = $app['db']->fetchAll($sql);

    return $app->json($data);
})->bind('getCompanies');

$app->get('/get/packages', function (Request $request) use ($app) {
    $sql = "SELECT 
                    `s`.`id` AS `service_id`,
                    `p`.`id` AS `id`,
                    `p`.`name` AS `package_name`,
                    `p`.`price`,
                    `c`.`name` AS `company_name`
                    FROM `services` `s`
                    JOIN `company_service` `cs`
                        ON `cs`.`services_id` = ?
                    JOIN `packages` `p`
                        ON `p`.`company_service_id` = `cs`.`id`
                    JOIN `companies` `c`
                        ON `c`.`id` = `cs`.`companies_id`
                        GROUP BY `p`.`id`";

    $serviceId = $request->query->get('service_id');
    $data = $app['db']->fetchAll($sql, array($serviceId));

    $dataOutput = [];
    $i = 0;
    foreach ($data as $package) {
        $sql = "        SELECT DISTINCT
                                `sc`.`id` AS `id`,
                                `pc`.`value`,
                                `sc`.`units`,
                                `sc`.`name`,
                                `sc`.`type`,
                                `sc`.`alias`
                                FROM `package_characteristics` `pc`
                                JOIN `service_characteristics` `sc`
                                    ON `sc`.`id` = `pc`.`service_characteristics_id`
                                JOIN `company_service` `cs`
                                    ON `cs`.`services_id` = ?
                                WHERE `pc`.`packages_id` = ?";
        array_push($dataOutput, $package);
        $dataChar = $app['db']->fetchAll($sql, array($package['service_id'], $package['id']));
        $dataOutput[$i++]['characteristics'] = count($dataChar) ? $dataChar : [];
    }

    return $app->json($dataOutput);
})->bind('getPackages');

$app->get('/get/service/characteristics', function (Request $request) use ($app) {
    $sql = "SELECT 
                    `s`.`id`,
                    `s`.`name`
                    FROM `services` `s`
                    WHERE `s`.`id` = ?";

    $serviceId = $request->query->get('service_id');
    $data = $app['db']->fetchAll($sql, array($serviceId));

    $dataOutput = [];
    $i = 0;
    foreach ($data as $service) {
        $sql = "        SELECT 
                                `sc`.`id`,
                                `sc`.`units`,
                                `sc`.`name`,
                                `sc`.`type`,
                                `sc`.`alias`
                                FROM `service_characteristics` `sc`
                                    WHERE `sc`.`services_id` = ?";

        array_push($dataOutput, $service);
        $dataChar = $app['db']->fetchAll($sql, array($service['id']));
        $dataOutput[$i++]['characteristics'] = count($dataChar) ? $dataChar : [];
    }

    return $app->json($dataOutput);
})->bind('getServiceCharacteristics');

$app->get('/get/min-max', function (Request $request) use ($app) {
    $sql = "SELECT
                  `sc`.`id` AS `sc_id`,
                  `pc`.`id` AS `pc_id`,
                  MIN(`pc`.`value`) AS `minValue`,
                  MAX(`pc`.`value`) AS `maxValue`
                  FROM `service_characteristics` `sc`
                  JOIN `package_characteristics` `pc`
                    ON `pc`.`service_characteristics_id` = `sc`.`id`
                  WHERE `sc`.`services_id` = ?
                  GROUP BY `pc`.`service_characteristics_id`
                  ";

    $serviceId = $request->query->get('service_id');
    $data = $app['db']->fetchAll($sql, array($serviceId));

    $i = 0;
    foreach ($data as $valueData) {
        $data[$i]['minValue'] = ($valueData['minValue'] == "nelimitat") ? 99999 : $valueData['minValue'];
        $data[$i]['maxValue'] = ($valueData['maxValue'] == "nelimitat") ? 99999 : $valueData['maxValue'];
        $i++;
    }

    return $app->json($data);
})->bind('getMinMax');

$app->get('/user/auth', function (Request $request) use ($app) {
    $username = $request->query->get('username');
    $password = $request->query->get('password');
    $secretKey = $request->query->get('secretKey');

    // if user and password given -> secret key
    if (!empty($username) && !empty($password)) {
        $sql = "
            SELECT 
            COUNT(*) AS `result_nr`,
            `u`.`id` AS `user_id`,
            `ak`.`key`
            FROM `users` `u`
            LEFT JOIN `auth_keys` `ak`
              ON `ak`.`user_id` = `u`.`id`
            WHERE `u`.`username` = ?
              AND `u`.`password` = PASSWORD(?)
        ";

        $dataQuery = $app['db']->fetchAssoc($sql, array($username, $password));

        if ($dataQuery['result_nr'] == 1) {
            $data = [
                        'status' => '200',
                        'message' => '',
                    ];

            if (!empty($dataQuery['key'])) {
                $sql = "
                    DELETE
                    FROM `auth_keys`
                    WHERE `user_id` = ?
                ";

                $app['db']->query($sql, array($dataQuery['user_id']));

                $sql = "
                    INSERT INTO `auth_keys` (`user_id`, `key`, `date_created`)
                      VALUES (?, ?, NOW())
                ";

                $authKey = md5(rand(1, 9999) . rand(4, 500) . rand(1, 3012) . date("d-m-Y H:i:s") . $dataQuery['user_id']);
                if (!$app['db']->query($sql, array($dataQuery['user_id'], $authKey))) {
                    $data = [
                        'status' => '500',
                        'message' => 'Something went wrong logging in',
                    ];
                } else {
                    $data = [
                        'status' => 200,
                        'message' => 'An authentication key has been generated',
                        'key' => $authKey,
                    ];
                }
            } else {
                $sql = "
                    INSERT INTO `auth_keys` (`user_id`, `key`, `date_created`)
                      VALUES (?, ?, NOW())
                ";

                $authKey = md5(rand(1, 9999) . rand(4, 500) . rand(1, 3012) . date("d-m-Y H:i:s") . $dataQuery['user_id']);
                if (!$app['db']->query($sql, array($dataQuery['user_id'], $authKey))) {
                    $data = [
                        'status' => '500',
                        'message' => 'Something went wrong logging in',
                    ];
                }
            }
        } else {
            $data = [
                        'status' => '400',
                        'message' => 'Wrong credentials',
                    ];
        }
    } else if (!empty($secretKey)) {
        $sql = "
            SELECT 
              COUNT(*) AS `result_nr`,
              `u`.`username` AS `username`,
              `u`.`email` AS `email`,
              `u`.`date_created` AS `date_created`
            FROM `auth_keys` `ak`
            JOIN `users` `u`
              ON `u`.`id` = `ak`.`id`
            WHERE `ak`.`key` = ?
        ";

        $dataQuery = $app['db']->fetchAssoc($sql, array($secretKey));

        if ($dataQuery['result_nr'] == 1) {
            $data = [
                'status' => '200',
                'message' => '',
                'userData' => [
                                'username' => $dataQuery['username'],
                                'email' => $dataQuery['email'],
                                'date_crated' => $dataQuery['date_created'],
                              ]
            ];
            // TODO: Logged in functionalities
        } else {
            $data = [
                'status' => '400',
                'message' => 'Invalid auth key provided',
            ];
        }
    } else {
        $data = [
                    'status' => '400',
                    'message' => 'Wrong parameters',
                ];
    }

    return $app->json($data);
})->bind('doLogin');

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
