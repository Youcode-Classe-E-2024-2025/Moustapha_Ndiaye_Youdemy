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
}
?>