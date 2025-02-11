SET search_path TO raffia;

INSERT INTO "user" (name, username, password, description, email, image, is_blocked, is_admin)
VALUES 
    ('Daniel Teixeira', 'dteixeira', '$2y$10$QR3JhDyIDrLqaxSi5ya0YOno9kbFxpdWb6v.BYEqA0b8jXewUWx9e', 'Food lover', 'wagner.d.teixeira@gmail.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Alice Smith', 'alice123', '$2y$10$Wttbk/LUKRL1IsDZ0G1Pu.Y3aGDIYAWD5QNvzhIQMOT55PVTVnqhW', 'Loves cooking', 'alice@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Bob Brown', 'bobbie', '$2y$10$hVxpfToHy9tGSiCxSE0G1.KpJgh5QgU/0rVt8bZJQpHgeRwyhY/xW', 'Food blogger', 'bob@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Charlie Chef', 'chefcharlie', '$2y$10$SnfZcOtSL/OP2QV1xmR7dOcGKj.Sk0lO6N5jweh0W95yoQTbpp1wy', 'Master chef', 'charlie@example.com', 'images/profile/default.png', FALSE, FALSE),
    ('Daniel Delgado', 'daniel_foodie', '$2y$10$rbbYdTkGDPSJyCATVYkjqe1XmgjCyV3ocznmNs4lSUsyMmUNC4BAi', 'Food lover and critic', 'daniel@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Emily Evans', 'emily_eats', '$2y$10$14mpvDysqUM7c2NX21tEzu1e59nM.2E89bDloQbn6/BI9QNboDN1y', 'Healthy eating advocate', 'emily@example.com', 'images/profile/default.png', FALSE, FALSE),    
    ('Fiona French', 'fiona_fries', '$2y$10$h2gmb6BMGw7auTVLCH0OnuVa3NHFtbr/qTVuTTQVWaalAm5F4p9eS', 'Blogger specializing in street food', 'fiona@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('George Gourmet', 'george_gourmet', '$2y$10$Fd6SUSN9b7SiQtXPVXT1PepaGO329u2a/xhZVH3R.YVHjI6ohZYMq', 'Fine dining enthusiast', 'george@example.com', 'images/profile/default.png', FALSE, TRUE), 
    ('Hannah Hernandez', 'hannah_harvest', '$2y$10$d3yj.tJUUKUarIc8RG22Tu5dsXxzWAjGyXpO4dXn.wJsN6yElWXSS', 'Vegan chef and recipe developer', 'hannah@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Isaac Irons', 'isaac_international', '$2y$10$OIWdEOVbdvlXUkqoUmvhau7BHT8S15GLt8CFtzv0/.f3V1M/wrXqa', 'Traveling chef exploring international cuisines', 'isaac@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Jasmine Jones', 'jasmine_jazzy', '$2y$10$m00v7NfDbt0udLwd7FnQB.NEpAMq0CNY/RgnPJ1JgSEme9QYpu3Gq', 'Enthusiast of Mediterranean flavors', 'jasmine@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Kevin Kim', 'kimchi_king', '$2y$10$bU./yz.sHymN0PLw4X8QMuVDW7E4AnmkC.IcNR86D3fC0Xx3Ydlwe', 'Korean BBQ expert', 'kevin@example.com', 'images/profile/default.png', FALSE, FALSE),
    ('Linda Liu', 'linda_luvs_food', '$2y$10$YBWPE5.AJ4LUMI0uudn2/OmcJMHPC1A/2cuC0.p49FdohxIIWAJc.', 'Passionate about Asian cuisine', 'linda@example.com', 'images/profile/default.png', FALSE, FALSE), 
    ('Mark Mendoza', 'mark_meat', '$2y$10$gBqH7.Ei2GwSLimHtHJ5VOQ1onHKQzeTrX.QcIphejBcHF7ahjPj.', 'Steakhouse owner and meat specialist', 'mark@example.com', 'images/profile/default.png', FALSE, TRUE),
    ('wagner','wagner','$2y$10$8rP6z/bm2eo5gUMxicvmoO29IM5vOSfrA3p1WgSYv89YzBhSurRtC','admin','wagner@gmail.com','images/profile/default.png',FALSE,TRUE),
    ('nelson','nelson','$2y$10$Cee/y9nfbGIPnpFQcU3wLewl1yv8T/t/LWiSwJjVF0GbZJCd4n7g2','admin','nelson@gmail.com','images/profile/default.png',FALSE,TRUE),
    ('sara','sara','$2y$10$5qrciNdQcFSMNiwbbBdeGu2qJXwegJbAP28RZPSexWEmJ9C03tz5C','admin','sara@gmail.com','images/profile/default.png',FALSE,TRUE),
    ('paulo','paulo','$2y$10$YohuXDZzAHsKM4HBgyfDFuTNZh6111OahVCyrNz5ISXOmng0VYqpG','admin','paulo@gmail.com','images/profile/default.png',FALSE,TRUE); 

INSERT INTO client (id)
VALUES 
    (1), 
    (2), 
    (3),
    (5), 
    (6), 
    (7), 
    (9), 
    (11), 
    (12), 
    (13),
    (15),
    (16),
    (17),
    (18);

INSERT INTO restaurant_type (name) VALUES
('American'),
('Chinese'),
('Italian'),
('Japanese'),
('Mexican'),
('Thai'),
('Portuguese'),
('Burger'),
('Pizza'),
('Indian'),
('French'),
('Greek'),
('Spanish'),
('Korean'),
('Vietnamese'),
('Lebanese'),
('Turkish'),
('Brazilian'),
('Argentinian'),
('Caribbean'),
('Mediterranean'),
('Moroccan'),
('Ethiopian'),
('German'),
('Russian'),
('Cuban'),
('Peruvian'),
('Filipino'),
('Malaysian'),
('Indonesian'),
('Hawaiian'),
('Vegan'),
('Vegetarian'),
('Seafood'),
('Steakhouse'),
('BBQ'),
('Fast Food'),
('Diner'),
('Cafe'),
('Bakery'),
('Dessert'),
('Sushi'),
('Tapas'),
('Middle Eastern'),
('Fusion'),
('Gluten-Free'),
('Organic'),
('Farm-to-Table'),
('Other');

INSERT INTO restaurant (id, rating_average, type_id, capacity)
VALUES 
    (4, 4.5, 3, 50),
    (8, 4.7, 3, 80), 
    (10, 4.8, 3, 60), 
    (14, 4.6, 3, 120);

INSERT INTO "group" (name, description, is_public, owner_id)
VALUES 
    ('Food Lovers', 'A group for anyone passionate about trying new foods and sharing experiences.', TRUE, 2),
    ('Vegan Ventures', 'A group for those interested in vegan and plant-based cuisine.', TRUE, 3),
    ('BBQ Masters', 'Dedicated to fans of barbecue and smoked meats.', TRUE, 5),
    ('Fine Dining Fans', 'For lovers of high-end cuisine and gourmet dining.', FALSE, 17),
    ('Street Food Explorers', 'A community for those who enjoy discovering street food from around the world.', TRUE, 7),
    ('Mediterranean Magic', 'Group focused on Mediterranean recipes and restaurant recommendations.', TRUE, 11),
    ('Asian Cuisine Enthusiasts', 'For people who love East and Southeast Asian foods.', TRUE, 13),
    ('Healthy Eating Advocates', 'A community for those interested in healthy and nutritious meals.', TRUE, 6),
    ('World of Desserts', 'For those with a sweet tooth and an interest in desserts.', TRUE, 2),
    ('Fusion Foodies', 'A group exploring fusion cuisine and unique flavor combinations.', TRUE, 12);


INSERT INTO group_member (client_id, group_id)
VALUES 
    (3,1),
    (1,2),
    (2,2),
    (3,3),
    (5,1),
    (6,1),
    (6,2),
    (16,1),
    (17,3),
    (3,4),
    (5,4);

INSERT INTO post (datetime, content, images) 
VALUES 
    ('2024-12-20 17:00:00', 'This is my first post about Italian cuisine!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:01:00', 'Check out this amazing pasta recipe!', '["images/postImages/postDefault.png", "images/postImages/postDefault.png"]'),
    ('2024-12-20 17:02:00', 'Exploring the best pizza places in town!', '["images/postImages/postDefault.png", "images/postImages/postDefault.png"]'),
    ('2024-12-20 17:03:00', 'Found a delicious vegan burger spot!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:04:00', 'Check out these grilled ribs from last weekend!', '["images/postImages/postDefault.png", "images/postImages/postDefault.png"]'),
    ('2024-12-20 17:05:00', 'Good recipe of pasta', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:06:00', 'Not so good food', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:07:00', 'I had dinned with Wagner', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:08:00', 'Exploring the best sushi places in town!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:09:00', 'Found a delicious vegan burger spot!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:10:00', 'Check out these grilled ribs from last weekend!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:11:00', 'Review of the new Italian restaurant in town', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:12:00', 'Best coffee shops to work from', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:13:00', 'Top 5 dessert places you must try', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:14:00', 'Healthy eating tips and tricks', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:15:00', 'Exploring street food in Bangkok', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:16:00', 'A guide to the best BBQ joints', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:17:00', 'Vegan recipes for beginners', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:18:00', 'The ultimate guide to French pastries', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:19:00', 'Top 10 sushi restaurants in Tokyo', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:20:00', 'Exploring the food markets of Mexico City', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:21:00', 'Best places to eat in New York City', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:22:00', 'A review of the new vegan restaurant', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:23:00', 'Top 5 pizza places in Italy', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:24:00', 'Healthy smoothie recipes', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:25:00', 'Exploring the best seafood restaurants', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:26:00', 'A guide to the best steakhouses', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:27:00', 'Top 10 brunch spots', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:28:00', 'Discovering hidden gems in the city', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:29:00', 'A day in the life of a food blogger', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:30:00', 'The best food festivals around the world', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:31:00', 'Exploring the culinary scene in Paris', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:32:00', 'A guide to the best street food', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:33:00', 'Top 10 food trucks you must try', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:34:00', 'Healthy meal prep ideas', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:35:00', 'The best places for brunch in LA', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:36:00', 'Exploring the best food markets', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:37:00', 'A guide to the best vegan restaurants', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:38:00', 'Top 10 coffee shops in Seattle', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:39:00', 'Exploring the hidden gems of our city!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:40:00', 'Top 10 vegan restaurants you must try!', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:41:00', 'A guide to the best coffee shops in town', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:42:00', 'Delicious homemade pasta recipes', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:43:00', 'The ultimate burger guide', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:44:00', 'Best places for brunch this weekend', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:45:00', 'Exploring the local food markets', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:46:00', 'Top 5 sushi spots in the city', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:47:00', 'A day in the life of a food blogger', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:48:00', 'Healthy and delicious smoothie recipes', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:49:00', 'The best street food in our city', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:50:00', 'A guide to the best steakhouses', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:51:00', 'Top 10 dessert places you must visit', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:52:00', 'Exploring the best seafood restaurants', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:53:00', 'A review of the new Italian restaurant', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:54:00', 'The best places for breakfast', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:55:00', 'A guide to the best BBQ joints', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:56:00', 'Top 5 pizza places in the city', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:57:00', 'Healthy eating tips and tricks', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:58:00', 'Exploring the best food festivals', '["images/postImages/postDefault.png"]'),
    ('2024-12-20 17:59:00', 'A guide to the best vegan dishes', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:00:00', 'Top 10 coffee shops to work from', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:01:00', 'Exploring the culinary scene in Paris', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:02:00', 'A guide to the best street food', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:03:00', 'Top 10 food trucks you must try', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:04:00', 'Healthy meal prep ideas', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:05:00', 'The best places for brunch in LA', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:06:00', 'Exploring the best food markets', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:07:00', 'A guide to the best vegan restaurants', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:08:00', 'Top 10 coffee shops in Seattle', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:09:00', 'Exploring the best food trucks', '["images/postImages/postDefault.png"]'),
    ('2023-01-01 11:10:00', 'Wagner is a great food enthusiast!', '["images/postImages/postDefault.png"]');

INSERT INTO review_post (id, rating, client_id, restaurant_id)
VALUES 
    (3, 3, 3, 4),
    (4, 4, 5, 4),
    (5, 3, 6, 4);

INSERT INTO review_post (id, rating, client_id, restaurant_id, group_id)
VALUES 
    (6, 3, 16, 4, 1),
    (7, 4, 16, 4, 1),
    (8, 4, 3, 4, 4),
    (40, 4, 3, 4, 4),
    (41, 5, 5, 4, 4),
    (42, 3, 3, 4, 4),
    (43, 4, 5, 4, 4),
    (44, 5, 3, 4, 4),
    (45, 4, 5, 4, 4),
    (46, 3, 3, 4, 4),
    (47, 4, 5, 4, 4),
    (48, 5, 3, 4, 4),
    (49, 4, 5, 4, 4),
    (50, 3, 3, 4, 4),
    (51, 4, 5, 4, 4),
    (52, 5, 3, 4, 4),
    (53, 4, 5, 4, 4),
    (54, 3, 3, 4, 4),
    (55, 4, 5, 4, 4),
    (56, 5, 3, 4, 4),
    (57, 4, 5, 4, 4),
    (58, 3, 3, 4, 4),
    (59, 4, 5, 4, 4),
    (60, 5, 3, 4, 4),
    (61, 4, 5, 4, 4),
    (62, 3, 3, 4, 4),
    (63, 4, 5, 4, 4),
    (64, 5, 3, 4, 4),
    (65, 4, 5, 4, 4),
    (66, 3, 3, 4, 4),
    (67, 4, 5, 4, 4),
    (68, 5, 3, 4, 4),
    (69, 4, 5, 4, 4),
    (70, 3, 3, 4, 4),
    (71, 3, 3, 4, 2);

INSERT INTO informational_post (id, restaurant_id)
VALUES
    (1,4),
    (2,8),
    (9, 4),
    (10, 4),
    (11, 4),
    (12, 4),
    (13, 4),
    (14, 4),
    (15, 4),
    (16, 4),
    (17, 4),
    (18, 4),
    (19, 4),
    (20, 4),
    (21, 4),
    (22, 4),
    (23, 4),
    (24, 4),
    (25, 4),
    (26, 4),
    (27, 4),
    (28, 4),
    (29, 4),
    (30, 4),
    (31, 4),
    (32, 4),
    (33, 4),
    (34, 4),
    (35, 4),
    (36, 4),
    (37, 4),
    (38, 4),
    (39, 4);



INSERT INTO comment (content, post_id, user_id)
VALUES 
    ('Looks delicious!', 1, 3),
    ('Can I get the recipe for that pasta?', 2, 5),
    ('This pizza looks amazing! Where did you get it?', 1, 6),
    ('I love Italian food! This post made my day!', 1, 7),
    ('What a great idea for dinner!', 2, 11),
    ('I need to try making that lasagna!', 1, 12);


INSERT INTO like_post (user_id, post_id)
VALUES 
    (1, 1),
    (2, 2),
    (1, 3),
    (2, 4);
    

INSERT INTO request_follow (requester_client_id, receiver_client_id)
VALUES 
    (2, 1); 

INSERT INTO join_requests (client_id, group_id)
VALUES 
    (2, 1); 
   
INSERT INTO comment_relationship (child, parent)
VALUES 
    (1, 1); 


INSERT INTO request_follow (datetime, requester_client_id, receiver_client_id)
VALUES 
    (NOW(), 3, 2);

INSERT INTO join_requests (client_id, group_id)
VALUES 
    (3, 1);


INSERT INTO follows_client (sender_client_id, followed_client_id)
VALUES 
    (3, 2);