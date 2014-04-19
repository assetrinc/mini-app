<?php
/*
 * Assetrinc mini-app source code
 *
 * Copyright Matt Light <matt.light@lightdatasys.com>
 *
 * For copyright and licensing information, please view the LICENSE
 * that is distributed with this source code.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Lstr\Silex\Asset\AssetServiceProvider;
use Lstr\Silex\Config\ConfigServiceProvider;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app->register(new ConfigServiceProvider());
$app->register(new AssetServiceProvider());

$app['config'] = $app['lstr.config']->load(array(
    __DIR__ . '/../config/autoload/*.global.php',
    __DIR__ . '/../config/autoload/*.local.php',
));

if (isset($app['config']['debug'])) {
    $app['debug'] = $app['config']['debug'];
}


$app->get('/asset/{version}/{name}', function (
    $version,
    $name,
    Application $app,
    Request $request
) {
    return $app['lstr.asset.responder']->getResponse(
        $name,
        array(
            'request' => $request,
        )
    );
})->assert('name', '.*');

$app->get('/', function () {
    return '';
});


$app->run();
