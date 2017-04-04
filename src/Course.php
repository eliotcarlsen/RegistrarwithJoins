<?php
    class Course
    {
        private $course_title;
        private $course_code;
        private $id;


        function __construct($course_title, $course_code, $id = null)
        {
            $this->course_title = $course_title;
            $this->course_code = $course_code;
            $this->id = $id;
        }

        function setCourseTitle($course_title)
        {
            $this->course_title = (string) $course_title;
        }

        function getCourseTitle()
        {
            return $this->course_title;
        }

        function setCourseCode()
        {
            $this->course_code = $course_code;
        }

        function getCourseCode()
        {
            return $this->course_code;
        }

        function setCourseId()
        {
          $this->id = $id;
        }

        function getCourseId()
        {
          return $this->id;
        }

        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO courses (course_title, course_code) VALUES ('{$this->getCourseTitle()}', '{$this->getCourseCode()}');");
            if ($executed) {
              $this->id= $GLOBALS['DB']->lastInsertId();
              return true;
            } else {
              return false;
            }
        }

        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $courses = array();
            foreach($returned_courses as $course) {
              $id = $course['id'];
              $course_title = $course['course_title'];
              $course_code = $course['course_code'];
              $new_course = new Course($course_title, $course_code, $id);
              array_push($courses, $new_course);
            }
            return $courses;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        function update($new_course_title)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE courses SET course_title = '{$new_course_title}' WHERE id = {$this->getCourseId()};");
            if ($executed) {
              $this->setCourseTitle($new_course_title);
              return true;
            } else {
              return false;
            }
        }

        static function find($search_id)
        {
            $returned_courses = $GLOBALS['DB']->prepare("SELECT * FROM courses WHERE id = :id");
            $returned_courses->bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_courses->execute();
            foreach($returned_courses as $course){
              $course_title = $course['course_title'];
              $course_code = $course['course_code'];
              $id = $course['id'];
              if($id == $search_id){
                $found_course = new Course($course_title, $course_code, $id);
                return $found_course;
              }
            }

        }

    }
?>
