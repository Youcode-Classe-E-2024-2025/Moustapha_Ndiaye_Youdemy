<?php
require_once __DIR__ . '/../core/Database.php';

class Course{
    private $pdo;

    public function __construct() {
        $database = Database::getInstance();
        $this->pdo = $database->getPdo();
    }

    /**
     * Get all courses 
     * @return array 
     */
    public function getAllCourses(): array {
        $query = "
            SELECT 
                Courses.id, 
                Courses.title, 
                Courses.description, 
                Courses.image_uri, 
                Categories.name AS category 
            FROM Courses
            LEFT JOIN Categories ON Courses.category_id = Categories.id
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get courses by category
     * @param string $category
     * @return array
     */
    public function getCoursesByCategory(string $category): array {
        $query = "
            SELECT 
                Courses.id, 
                Courses.title, 
                Courses.description, 
                Courses.image_uri, 
                Categories.name AS category 
            FROM Courses
            LEFT JOIN Categories ON Courses.category_id = Categories.id
            WHERE Categories.name = :category
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * search courses
     * @param string $searchTerm
     * @return array
     */
    public function searchCourses(string $searchTerm): array {
        $query = "
            SELECT 
                Courses.id, 
                Courses.title, 
                Courses.description, 
                Courses.image_uri, 
                Categories.name AS category 
            FROM Courses
            LEFT JOIN Categories ON Courses.category_id = Categories.id
            WHERE Courses.title LIKE :searchTerm OR Courses.description LIKE :searchTerm
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':searchTerm' => "%$searchTerm%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Categories
     * @return array
     */
    public function getCategories(): array {
        $query = "SELECT name FROM Categories";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>