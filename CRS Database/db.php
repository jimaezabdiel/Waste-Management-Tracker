CREATE DATABASE waste_management;

USE waste_management;

CREATE TABLE waste_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waste_type VARCHAR(255),
    disposal_date DATETIME
);

