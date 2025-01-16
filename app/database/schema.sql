CREATE DATABASE IF NOT EXISTS Youdemy;

USE Youdemy;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Student', 'Instructor', 'Admin') NOT NULL,
    status ENUM('Active', 'Pending', 'Suspended') DEFAULT 'Pending' 
);

-- Table des Catégories (Categories)
CREATE TABLE Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table des Cours (Courses)
CREATE TABLE Courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT,
    category_id INT,
    FOREIGN KEY (instructor_id) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE SET NULL
);

-- Table des Tags
CREATE TABLE Tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table de Jointure Cours-Tags (CourseTags)
CREATE TABLE CourseTags (
    course_id INT,
    tag_id INT,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES Tags(id) ON DELETE CASCADE
);

-- Table des Inscriptions (Enrollments)
CREATE TABLE Enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Vidéos (Videos)
CREATE TABLE Videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_url VARCHAR(255) NOT NULL,
    duration INT,  -- Durée en secondes
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Contenus Markdown (MarkdownContents)
CREATE TABLE MarkdownContents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,  -- Contenu Markdown
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Quiz (Quizzes)
CREATE TABLE Quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Questions (Questions)
CREATE TABLE Questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question TEXT NOT NULL,
    option1 VARCHAR(255),
    option2 VARCHAR(255),
    option3 VARCHAR(255),
    option4 VARCHAR(255),
    correct_answer INT,  -- Index de la bonne réponse (1 à 4)
    FOREIGN KEY (quiz_id) REFERENCES Quizzes(id) ON DELETE CASCADE
);

-- Table des Statistiques des Cours (CourseStatistics)
CREATE TABLE CourseStatistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    enrolled_students INT DEFAULT 0,
    engagement_rate FLOAT DEFAULT 0.0,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Statistiques des Étudiants (StudentStatistics)
CREATE TABLE StudentStatistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    progress FLOAT DEFAULT 0.0,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(id) ON DELETE CASCADE
);

-- Table des Statistiques Globales (GlobalStatistics)
CREATE TABLE GlobalStatistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_courses INT DEFAULT 0,
    category_distribution JSON, -- Stocke la répartition des cours par catégorie
    top_courses JSON, -- Stocke les cours les plus populaires
    top_instructors JSON, -- Stocke les enseignants les plus populaires
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
