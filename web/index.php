<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;



$app = new Silex\Application();

$app['debug']=true;

$app->register(new FormServiceProvider());

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

$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->get('/', function () use ($app){
	return $app['twig']->render('index.twig');




});

$app->match('/registration', function (Request $request) use ($app) {

	$form = $app['form.factory']->createBuilder('form')
        ->add('name', null, array(
        	'label' => 'Имя'
        	))
        ->add('surname')
        ->add('byear')
        ->add('email')
        ->add('category', 'choice', array(
            'choices' => array(1 => 'male', 2 => 'female'),
            'label' => 'Категория'
            ))
        ->getForm();

	$form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();


        $app['db']->insert('riders', $data);
    }


    return $app['twig']->render('hello.twig', array('form' => $form->createView()));



});


$app->get('/admin', function () use ($app) {
	$riders = $app['db']->fetchAll('SELECT * FROM riders');
//	var_dump($riders);
    return $app['twig']->render('admin.twig', array(
	'riders' => $riders,
	));
});
$app->run();


