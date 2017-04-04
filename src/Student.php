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
                $found_student = new Student($student_name, $enroll_date, $student_id);
              }
            }
            return $found_student;
        }

    }
?>
