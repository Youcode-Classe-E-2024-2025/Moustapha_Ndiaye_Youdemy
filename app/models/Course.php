<?php

namespace App\Models;
class CourseModel {
    private $pdo;

    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAllCoursesDetails() {
        $sql = "SELECT * FROM Courses";
        return $this->pdo->fetch($sql); 
    }
}