<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
			'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'silex',
            'user'      => 'root',
            'password'  => '',
            'charset'   => 'utf8',
        ),
));

$app->get('/admin', function () use ($app) {
	$riders = $app['db']->fetchAll('SELECT * FROM riders');
//	var_dump($riders);
    return $app['twig']->render('admin.twig', array(
	'riders' => $riders,
	));
});
$app->run();
