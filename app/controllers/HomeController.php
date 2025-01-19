<?php

namespace App\Controllers;

class CourseController {
    private $courseModel;

    public function __construct(\App\Models\CourseModel $courseModel) {
        $this->courseModel = $courseModel;
    }
}