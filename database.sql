CREATE DATABASE IF NOT EXISTS nalla_chuvadu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nalla_chuvadu;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('member','admin') NOT NULL DEFAULT 'member',
  points INT NOT NULL DEFAULT 0,
  streak INT NOT NULL DEFAULT 0,
  longest_streak INT NOT NULL DEFAULT 0,
  last_completed_on DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(50) NOT NULL,
  points INT NOT NULL DEFAULT 25,
  icon VARCHAR(8) NOT NULL DEFAULT '✨',
  active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE daily_tasks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  task_id INT UNSIGNED NOT NULL,
  assigned_on DATE NOT NULL,
  UNIQUE KEY one_task_per_day (user_id, assigned_on),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
);

CREATE TABLE submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  daily_task_id INT UNSIGNED NOT NULL,
  description TEXT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  admin_note TEXT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  reviewed_at TIMESTAMP NULL,
  UNIQUE KEY one_submission (daily_task_id),
  FOREIGN KEY (daily_task_id) REFERENCES daily_tasks(id) ON DELETE CASCADE
);

CREATE TABLE badges (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  icon VARCHAR(8) NOT NULL,
  threshold INT NOT NULL
);

CREATE TABLE user_badges (
  user_id INT UNSIGNED NOT NULL,
  badge_id INT UNSIGNED NOT NULL,
  awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, badge_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
);

INSERT INTO tasks (title, description, category, points, icon) VALUES
('Plant a little hope', 'Plant a seed, sapling, or care for a green space near you.', 'Environment', 30, '🌱'),
('Neighbourly moment', 'Offer practical help to a neighbour, especially someone who may need it.', 'Community', 25, '🤝'),
('Share a skill', 'Teach someone one useful skill, tip, or lesson today.', 'Education', 30, '📚'),
('Share a meal', 'Share food with a person, family, or community effort.', 'Kindness', 35, '🍲'),
('Give useful items', 'Donate clean, usable items to someone who can use them.', 'Giving', 25, '🎁'),
('Clean one corner', 'Pick up litter or improve a small shared space.', 'Environment', 25, '🧹');

INSERT INTO badges (name, description, icon, threshold) VALUES
('First Step', 'Complete your first positive quest.', '🌟', 25),
('Kindness Keeper', 'Earn 100 points for your community.', '💛', 100),
('Seven Day Spark', 'Keep a 7 day positive streak.', '🔥', 175),
('Community Champion', 'Earn 300 points through consistent action.', '🏆', 300);
