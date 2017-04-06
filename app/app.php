<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";
    require_once __DIR__."/../src/Department.php";
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
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

    $app->get("/new_course", function() use ($app){
      return $app['twig']->render('course.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/new_course", function() use ($app){
      $course = new Course($_POST['course_title'],$_POST['course_code']);
      $course->save();
      $course_id = $course->getCourseId();
      $dept_array = Department::getDeptArray();
      if(in_array($_POST['department_name'], $dept_array))
      {
        $new_department = Department::findDeptByName($_POST['department_name']);
        $results = $new_department->coursesForDeparts();
        $new_department->joinSave($course_id);
        $departments = Department::getAll();
        $courses = $new_department->getCourses();
        return $app['twig']->render('course.html.twig', array('courses' => Course::getAll(), 'departments' => Department::getAll(), 'department_courses' => $courses, 'results'=>$results));
      } else {
        $new_department = new Department($_POST['department_name']);
        $new_department->save();
        $results = $new_department->coursesForDeparts();
        $new_department->joinSave($course_id);
        $departments = Department::getAll();
        $courses = $new_department->getCourses();
        return $app['twig']->render('course.html.twig', array('courses' => Course::getAll(), 'departments' => Department::getAll(), 'department_courses' => $courses, 'results'=>$results));
      }
    });

    $app->get("/new_course/{id}", function($id) use ($app){
      $current_course = Course::find($id);
      $all_students = $current_course->getStudents();
      return $app['twig']->render('courses_edit.html.twig', array('courses' => $current_course, 'students' => $all_students));
    });

    $app->post("/new_student", function() use ($app){
      $student = new Student($_POST['student_name']);
      $student->save();
      $results = $_POST['course_select'];
      foreach($results as $result){
          $student->joinSave($result);
      }
      $all_courses = $student->getCourses();

      return $app['twig']->render('student.html.twig', array('students' => $student, 'courses' => $all_courses));
    });

    // creates area to edit student name, enroll date and courses on the student.html page
    $app->get("/new_student/{id}", function($id) use ($app){
      $current_student = Student::find($id);
      $all_courses = $current_student->getCourses();
      $courses = Course::getAll();
      return $app['twig']->render('student_edit.html.twig', array('students' => $current_student, 'courses' => $all_courses, 'all_courses' => $courses));
    });

    $app->get("/edit_student/{id}", function($id) use($app) {
      $student = Student::find($id);
      $all_courses = $student->getCourses();
      $courses = Course::getAll();
      return $app['twig']->render('student_edit.html.twig', array('students' => $student, 'courses' => $all_courses, 'all_courses' => $courses));
    });

    $app->patch("/edit_student/{id}", function($id) use($app) {
      $student = Student::find($id);
      $name = $_POST['student_name'];
      $student->update($name);
      $courses = Course::getAll();
      $all_courses = $student->getCourses();
      return $app['twig']->render('student_edit.html.twig', array('students' => $student, 'courses' => $all_courses, 'all_courses' => $courses));
    });

    $app->get("/all_students", function() use ($app){
      $course = Course::getAll();
      return $app['twig']->render('all_students.html.twig', array('students' => Student::getAll(), 'course' => $course));
    });

    return $app;
 ?>
