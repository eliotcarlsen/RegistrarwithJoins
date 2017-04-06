<?php
    class Department
    {
        private $department_name;
        private $id;


        function __construct($department_name, $id = null)
        {
            $this->department_name = $department_name;
            $this->id = $id;
        }

        function setDepartmentName($department_name)
        {
            $this->department_name = (string) $department_name;
        }

        function getDepartmentName()
        {
            return $this->department_name;
        }

        function setDepartmentId()
        {
          $this->id = $id;
        }

        function getDepartmentId()
        {
          return $this->id;
        }

        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO departments (department_name) VALUES ('{$this->getDepartmentName()}');");
            if ($executed) {
              $this->id= $GLOBALS['DB']->lastInsertId();
              return true;
            } else {
              return false;
            }
        }

        function joinSave($course_id)
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO courses_departments (course_id, department_id) VALUES ($course_id, '{$this->getDepartmentId()}');");
            if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        function getCoursesUsingJoin()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM departments JOIN courses_departments ON (courses_departments.department_id = departments.id) JOIN courses ON (courses.id = courses_departments.course_id) WHERE departments.id = {$this->getDepartmentId()};");
            $courses = array();
            foreach($returned_courses as $course) {
              $title = $course['course_title'];
              $code = $course['course_code'];
              $id = $course['id'];
              $new_course = new Course($title, $code, $id);
              array_push($courses, $new_course);
            }
            return $courses;
        }

        function getCourses()
        {
            $query = $GLOBALS['DB']->query("SELECT course_id FROM courses_departments WHERE department_id = {$this->getDepartmentId()};");
            $course_ids = $query->fetchAll(PDO::FETCH_ASSOC);
            $courses = array();
            foreach($course_ids as $id) {
                $course_id = $id['course_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$course_id};");
                $returned_course = $result->fetchAll(PDO::FETCH_ASSOC);
                $course_title = $returned_course[0]['course_title'];
                $course_code = $returned_course[0]['course_code'];
                $id = $returned_course[0]['id'];
                $new_course = new Course($course_title, $course_code, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function getAll()
        {
            $returned_departments = $GLOBALS['DB']->query("SELECT * FROM departments;");
            $departments = array();
            foreach($returned_departments as $department) {
              $id = $department['id'];
              $department_name = $department['department_name'];
              $new_department = new Department($department_name, $id);
              array_push($departments, $new_department);
            }
            return $departments;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM departments;");
        }

        function update($new_department_name)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE departments SET department_name = '{$new_department_name}' WHERE id = {$this->getDepartmentId()};");
            if ($executed) {
              $this->setDepartmentName($new_department_name);
              return true;
            } else {
              return false;
            }
        }

        static function find($search_id)
        {
            $found_department = null;
            $returned_departments = $GLOBALS['DB']->prepare("SELECT * FROM departments WHERE id = :id");
            $returned_departments->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_departments->execute();
            foreach($returned_departments as $department){
              $department_name = $department['department_name'];
              $id = $department['id'];
              if($id == $search_id){
                $found_department = new Department($department_name, $id);
              }
            }
            return $found_department;
        }

      function coursesForDeparts(){
        $executed = $GLOBALS['DB']->query("SELECT courses.course_title, departments.department_name FROM courses JOIN courses_departments ON (courses_departments.course_id = courses.id) JOIN departments ON (departments.id = courses_departments.department_id);");
        $results = $executed->fetchAll(PDO::FETCH_ASSOC);
        return $results;
      }

      static function getDeptArray() {
          $dept_array = array ();
          $executed = $GLOBALS['DB']->query("SELECT * FROM departments");
          $results = $executed->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $result){
            array_push($dept_array, $result['department_name']);
          }
          return $dept_array;
      }

      static function findDeptByName($name) {
          $executed = $GLOBALS['DB']->prepare("SELECT * FROM departments WHERE department_name = :name");
          $executed->bindParam(':name', $name, PDO::PARAM_STR);
          $executed->execute();
          $dept = $executed->fetch(PDO::FETCH_ASSOC);
          $new_dept = new Department ($dept['department_name'], $dept['id']);
          return $new_dept;
      }

    }
?>
