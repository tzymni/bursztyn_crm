-- # Create an user with login test@test.pl and password Test123
INSERT INTO `user` (`id`, `email`, `password`, `is_active`, `first_name`, `last_name`) VALUES
(1, 'test@test.pl', '$2y$10$A7mtzjrQuSzruh4C9I2I3en45fESz1w4Fz.XUyZsMNXYSHlFx6k3m', 1, 'Test', 'Test');