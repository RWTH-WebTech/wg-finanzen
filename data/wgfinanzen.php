<?php 
$pdo = new PDO('sqlite:wgfinanzen.sqlite');
$pdo->exec("DROP TABLE IF EXISTS flatmate");
$pdo->exec("DROP TABLE IF EXISTS purchase");
$pdo->exec("DROP TABLE IF EXISTS purchased_for");
$pdo->exec("CREATE TABLE IF NOT EXISTS flatmate (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS purchase (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	title TEXT,
	description TEXT,
	date TEXT,
	cost REAL,
	purchased_by INTEGER
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS purchased_for (
	purchase_id INTEGER,
	flatmate_id INTEGER,
	PRIMARY KEY (purchase_id, flatmate_id)
)");
$pdo->exec("INSERT INTO flatmate (id, name) VALUES
(1, 'Alice'),
(2, 'Bob'),
(3, 'Eve')");
$pdo->exec("INSERT INTO purchase (id, title, description, date, cost, purchased_by) VALUES 
(1, 'Bäcker', '5 Brötchen, 3 Croissants', '2016-07-05 12:00:00', 4.3, 1),
(2, 'Supermarkt', 'Milch, Joghurt', '2016-07-05 12:30:00', 2.19, 1),
(3, 'Markt', 'Zucchini, Paprika, Zwiebeln', '2016-07-05 13:00:00', 5.42, 1),
(4, 'Supermarkt', 'Koffeinhaltiges Getränk', '2016-07-05 18:00:00', 1.49, 2)");
$pdo->exec("INSERT INTO purchased_for (purchase_id, flatmate_id) VALUES 
(1,1),
(1,2),
(1,3),
(2,1),
(3,1),
(3,2),
(3,3),
(4,3)");
