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

    class StudentTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Student::deleteAll();
          Course::deleteAll();
        }

        function test_getStudentName()
        {
            $student_name = "Tom Sawyer";
            $enroll_date = '2016-09-21';
            $test_name = new Student($student_name, $enroll_date);
            $result = $test_name->getStudentName();
            $this->assertEquals($student_name, $result);
        }

        function test_getEnrollDate()
        {
            $student_name = "Tom Sawyer";
            $enroll_date = '2016-09-21';
            $test_name = new Student($student_name, $enroll_date);
            $result = $test_name->getEnrollDate();
            $this->assertEquals($enroll_date, $result);
        }

        function test_save()
        {
            $student_name = "Tom Sawyer";
            $enroll_date = '2016-09-21';
            $test_student = new Student($student_name, $enroll_date);
            $test_student->save();

            $executed = $test_student->getStudentName();

            $this->assertContains("Tom Sawyer" ,$executed);
        }

        function test_getAll()
        {
            $student_name = "Tom Sawyer";
            $enroll_date = date('Y-m-d');
            $student_name2 = "Huckleberry Finn";
            $id= null;
            $test_student = new Student($student_name, $enroll_date, $id);
            $test_student->save();
            $test_student2 = new Student($student_name2, $enroll_date, $id);
            $test_student2->save();
            $result = Student::getAll();
            $this->assertEquals([$test_student, $test_student2], $result);
        }

        function test_deleteAll()
        {
            $student_name = "Tom Sawyer";
            $enroll_date = '2016-09-21';
            $student_name2 = "Huckleberry Finn";
            $enroll_date2 = '2015-09-27';
            $test_student = new Student($student_name, $enroll_date);
            $test_student->save();
            $test_student2 = new Student($student_name2, $enroll_date2);
            $test_student2->save();
            $result = Student::deleteAll();
            $everything = Student::getAll();
            $this->assertEquals([], $everything);
        }

        function test_update()
        {
          $student_name = "Tom Sawyer";
          $enroll_date = '2016-09-21';
          $test_student = new Student($student_name, $enroll_date);
          $test_student->save();

          $new_student_name = "Mark Twain";

          $test_student->update($new_student_name);

          $this->assertEquals("Mark Twain", $test_student->getStudentName());
        }

        function test_find()
        {
          $student_name = "Tom Sawyer";
          $enroll_date = date('Y-m-d');
          $student_name2 = "Huckleberry Finn";
          $test_student = new Student($student_name, $enroll_date);
          $test_student->save();
          $test_student2 = new Student($student_name2, $enroll_date);
          $test_student2->save();
          $result = Student::find($test_student->getStudentId());

          $this->assertEquals($test_student, $result);
        }
    }
?>
