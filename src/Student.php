<?php
    class Student
    {
        private $student_name;
        private $enroll_date;
        private $id;


        function __construct($student_name, $enroll_date=null, $id = null)
        {
            $this->student_name = $student_name;
            $this->enroll_date = $enroll_date;
            $this->id = $id;
        }

        function setStudentName($student_name)
        {
            $this->student_name = (string) $student_name;
        }

        function getStudentName()
        {
            return $this->student_name;
        }

        function setEnrollDate()
        {
            $this->enroll_date = $enroll_date;
        }

        function getEnrollDate()
        {
            return $this->enroll_date;
        }

        function setStudentId()
        {
          $this->id = $id;
        }

        function getStudentId()
        {
          return $this->id;
        }

        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO students (student_name, enroll_date) VALUES ('{$this->getStudentName()}', NOW());");
            if ($executed) {
              $this->id= $GLOBALS['DB']->lastInsertId();
              return true;
            } else {
              return false;
            }
        }

        function joinSave($course_id)
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO courses_students (student_id, course_id) VALUES ('{$this->getStudentId()}', $course_id);");
            if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        static function declareMajor($department_id)
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO departments_students (department_id, student_id) VALUES ($department_id, {$this->getStudentId()};)");
            if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        function getCoursesUsingJoin()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM students JOIN courses_students ON (courses_students.student_id = students.id) JOIN courses ON (courses.id = courses_students.course_id) WHERE students.id = {$this->getStudentId};");
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
            $query = $GLOBALS['DB']->query("SELECT course_id FROM courses_students WHERE student_id = {$this->getStudentId()};");
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
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
            $students = array();
            foreach($returned_students as $student) {
              $id = $student['id'];
              $student_name = $student['student_name'];
              $enroll_date = $student['enroll_date'];
              $new_student = new Student($student_name, $enroll_date, $id);
              array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        function update($new_student_name)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE students SET student_name = '{$new_student_name}' WHERE id = {$this->getStudentId()};");
            if ($executed) {
              $this->setStudentName($new_student_name);
              return true;
            } else {
              return false;
            }
        }

        function updateEnrollment($new_enrollment)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE students SET enroll_date = '{$new_enrollment}' WHERE id = {$this->getStudentId()};");
            if ($executed) {
              $this->setEnrollDate($new_enrollment);
              return true;
            } else {
              return false;
            }
        }

        static function find($search_id)
        {
            $found_student = null;
            $returned_students = $GLOBALS['DB']->prepare("SELECT * FROM students WHERE id = :id");
            $returned_students->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_students->execute();
            foreach($returned_students as $student){
              $student_name = $student['student_name'];
              $enroll_date = $student['enroll_date'];
              $id = $student['id'];
              if($id == $search_id){
                $found_student = new Student($student_name, $enroll_date, $id);
              }
            }
            return $found_student;
        }

    }
?>
