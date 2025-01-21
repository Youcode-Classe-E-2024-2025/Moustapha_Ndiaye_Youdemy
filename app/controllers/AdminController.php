<?php
require_once __DIR__ . '/../models/Admin.php';

class AdminController {
    private $adminModel;

    public function __construct(PDO $pdo) {
        $this->adminModel = new Admin($pdo);
    }

    /**
     *  get   globals statistics.
     * @return array
     */
    public function getGlobalStatistics(): array {
        return $this->adminModel->getGlobalStatistics();
    }

    /**
     * Get Recent Users
     * @return array
     */
    public function getRecentUsers(): array {
        return $this->adminModel->getRecentUsers();
    }

    /**
     * Get Recent Courses
     * @return array
     */
    public function getRecentCourses(): array {
        return $this->adminModel->getRecentCourses();
    }
}
?>