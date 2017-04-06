<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";
    require_once "src/Department.php";

    $server = 'mysql:host=localhost:8889;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class DepartmentTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Student::deleteAll();
          Department::deleteAll();
          Course::deleteAll();
        }

        function test_getDepartmentName()
        {
            $department_name = "Physics";
            $test_name = new Department($department_name);
            $result = $test_name->getDepartmentName();
            $this->assertEquals($department_name, $result);
        }

        function test_save()
        {
            $department_name = "Physics";
            $test_department = new Department($department_name);
            $test_department->save();

            $executed = $test_department->getDepartmentName();

            $this->assertContains("Physics" ,$executed);
        }

        function test_getAll()
        {
            $department_name = "Physics";
            $department_name2 = "History";
            $id= null;
            $test_department = new Department($department_name, $id);
            $test_department->save();
            $test_department2 = new Department($department_name2, $id);
            $test_department2->save();
            $result = Department::getAll();
            $this->assertEquals([$test_department, $test_department2], $result);
        }

        function test_deleteAll()
        {
            $department_name = "Physics";
            $department_name2 = "History";
            $test_department = new Department($department_name);
            $test_department->save();
            $test_department2 = new Department($department_name2);
            $test_department2->save();
            $result = Department::deleteAll();
            $everything = Department::getAll();
            $this->assertEquals([], $everything);
        }

        function test_update()
        {
          $department_name = "Physics";
          $test_department = new Department($department_name);
          $test_department->save();

          $new_department_name = "History";

          $test_department->update($new_department_name);

          $this->assertEquals("History", $test_department->getDepartmentName());
        }

        function test_find()
        {
          $department_name = "Physics";
          $department_name2 = "History";
          $test_department = new Department($department_name);
          $test_department->save();
          $test_department2 = new Department($department_name2);
          $test_department2->save();
          $result = Department::find($test_department->getDepartmentId());

          $this->assertEquals($test_department, $result);
        }
    }
?>
