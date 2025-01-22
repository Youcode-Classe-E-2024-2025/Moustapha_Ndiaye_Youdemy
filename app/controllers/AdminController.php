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

      /**
     * Met à jour le rôle d'un utilisateur.
     *
     * @param int $userId
     * @param string $newRole
     * @return array
     */
    public function updateUserRole(int $userId, string $newRole): array {
        try {
            $success = $this->adminModel->updateUserRole($userId, $newRole);
            return ['success' => $success, 'message' => $success ? 'Role updated successfully.' : 'Failed to update role.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    /**
     * Met à jour le statut d'un utilisateur.
     *
     * @param int $userId
     * @param string $newStatus
     * @return array
     */
    public function updateUserStatus(int $userId, string $newStatus): array {
        try {
            $success = $this->adminModel->updateUserStatus($userId, $newStatus);
            return ['success' => $success, 'message' => $success ? 'Status updated successfully.' : 'Failed to update status.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
    }
}
?>