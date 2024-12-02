CREATE TABLE waste_goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, 
    waste_type VARCHAR(255),  (plastic, organic, etc.)
    target_percentage DECIMAL(5, 2), 
    start_date DATETIME, 
    end_date DATETIME, 
    current_progress DECIMAL(5, 2), 
    goal_status ENUM('active', 'completed', 'failed') DEFAULT 'active'  
);


CREATE TABLE user_waste_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,  
    waste_type VARCHAR(255),  (plastic, organic, etc.)
    waste_amount DECIMAL(10, 2),  
    disposal_date DATETIME  
);
