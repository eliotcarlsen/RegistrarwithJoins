<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();

    $server = 'mysql:host=localhost:8889;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig', array ('courses' => Course::getAll()));
    });

    $app->post("/new_student", function() use ($app){
      $student = new Student($_POST['student_name']);
      $student->save();
      $results = $_POST['course_select'];
      foreach($results as $result){
          $student->joinSave($result);
      }
      return $app['twig']->render('student.html.twig', array('students' => $student));
    });

    $app->post("/new_course", function() use ($app){
      $course = new Course($_POST['course_title'], $_POST['course_code']);
      $course->save();
      return $app['twig']->render('test.html.twig', array('students' => Student::getAll(), 'courses' => Course::getAll()));
    });

    return $app;
 ?>
