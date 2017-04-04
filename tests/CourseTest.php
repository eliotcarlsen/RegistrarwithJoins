<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";

    $server = 'mysql:host=localhost:8889;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CourseTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Student::deleteAll();
          Course::deleteAll();
        }

        function test_getCourseTitle()
        {
            $course_title = "Intro to Programming";
            $course_code = 'CS101';
            $test_course_title = new Course($course_title, $course_code);
            $result = $test_course_title->getCourseTitle();
            $this->assertEquals($course_title, $result);
        }

        function test_getCourseCode()
        {
            $course_title = "Intro to Programming";
            $course_code = 'CS101';
            $test_course_title = new Course($course_title, $course_code);
            $result = $test_course_title->getCourseCode();
            $this->assertEquals($course_code, $result);
        }

        function test_save()
        {
            $course_title = "Intro to Programming";
            $course_code = 'CS101';
            $test_course = new Course($course_title, $course_code);
            $test_course->save();

            $executed = $test_course->getCourseTitle();

            $this->assertContains("Intro to Programming" ,$executed);
        }

        function test_getAll()
        {
            $course_title = "Intro to Programming";
            $course_code = 'CS101';
            $course_title2 = "Advanced Javascript";
            $course_code2 = 'CS201';
            $id= null;
            $test_course = new Course($course_title, $course_code, $id);
            $test_course->save();
            $test_course2 = new Course($course_title2, $course_code2, $id);
            $test_course2->save();
            $result = Course::getAll();
            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function test_deleteAll()
        {
            $course_title = "Intro to Programming";
            $course_code = 'CS101';
            $course_title2 = "Advanced Javascript";
            $course_code2 = 'CS201';
            $test_course = new Course($course_title, $course_code);
            $test_course->save();
            $test_course2 = new Course($course_title2, $course_code2);
            $test_course2->save();
            $result = Course::deleteAll();
            $everything = Course::getAll();
            $this->assertEquals([], $everything);
        }

        function test_update()
        {
          $course_title = "Intro to Programming";
          $course_code = 'CS101';
          $test_course = new Course($course_title, $course_code);
          $test_course->save();

          $new_course_title = "Advanced Javascript";

          $test_course->update($new_course_title);

          $this->assertEquals("Advanced Javascript", $test_course->getCourseTitle());
        }

        function test_find()
        {
          $course_title = "Intro to Programming";
          $course_code = 'CS101';
          $course_title2 = "Advanced Javascript";
          $course_code2 = 'CS201';
          $test_course = new Course($course_title, $course_code);
          $test_course->save();
          $test_course2 = new Course($course_title2, $course_code2);
          $test_course2->save();
          $result = Course::find($test_course->getCourseId());

          $this->assertEquals($test_course, $result);
        }
    }

?>
