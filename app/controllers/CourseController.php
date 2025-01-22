<?php
require_once __DIR__ . '/../models/Course.php';

class CourseController {
    private $studentModel;

    public function __construct() {
        $this->studentModel = new Course() ;
    }

    /**
     * Récupère tous les cours disponibles.
     * @return array
     */
    public function getAllCourses(): array {
        return $this->studentModel->getAllCourses();
    }

    public function getAllCoursesByUsers(int $studentId): array {
        return $this->studentModel->getAllCoursesByUsers($studentId);
    }
    /**
     * Récupère les cours par catégorie.
     * @param string $category
     * @return array
     */
    public function getCoursesByCategory(string $category): array {
        return $this->studentModel->getCoursesByCategory($category);
    }

    /**
     * Récupère les cours correspondant à une recherche.
     * @param string $searchTerm
     * @return array
     */
    public function searchCourses(string $searchTerm): array {
        return $this->studentModel->searchCourses($searchTerm);
    }

    /**
     * Récupère les catégories disponibles.
     * @return array
     */
    public function getCategories(): array {
        return $this->studentModel->getCategories();
    }

            /**
         * Enroll the current student in a course.
         * @param int $courseId
         * @param int $studentId
         * @return string
         */
        public function enrollCourse(int $courseId, int $studentId): string {
            try {
                $result = $this->studentModel->enrollStudent($courseId, $studentId);
                if ($result) {
                    return "Enrollment successful!";
                } else {
                    throw new Exception("Failed to enroll in the course.");
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }


            /**
 * Get course statistics for the logged-in instructor.
 * @return array
 */
public function getCourseStatistics(): array {
    $instructorId = $_SESSION['user']['id']; // Get the logged-in instructor's ID
    return $this->studentModel->getCourseStatistics($instructorId);
}

    /**
 * Get recent courses created by the logged-in instructor.
 * @return array
 */
public function getRecentCourses(): array {
    $instructorId = $_SESSION['user']['id']; // Get the logged-in instructor's ID
    return $this->studentModel->getRecentCourses($instructorId);
}

/**
 * Get recent students enrolled in courses created by the logged-in instructor.
 * @return array
 */
public function getRecentStudents(): array {
    $instructorId = $_SESSION['user']['id']; // Get the logged-in instructor's ID
    return $this->studentModel->getRecentStudents($instructorId);
}
}
?>