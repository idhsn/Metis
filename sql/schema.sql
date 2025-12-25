CREATE DATABASE Metis IF NOT EXISTS;
USE Metis;

CREATE TABLE members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATE NOT NULL
);

CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    title VARCHAR(50) NOT NULL,
    type ENUM('court','long') NOT NULL,
    descreption TEXT,
    created_at DATE NOT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id)
);

CREATE TABLE activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    title VARCHAR(50) NOT NULL,
    descreption TEXT,
    status ENUM('todo','doing','done') DEFAULT 'todo',
    created_at DATE NOT NULL,
    FOREIGN KEY project_id REFERENCES projects(id)
);