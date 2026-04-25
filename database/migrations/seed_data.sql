-- SIDAI RESORT — Seed Data
-- Sample data for development and testing

SET NAMES utf8mb4;

-- ━━ SAMPLE ROOMS ━━
INSERT IGNORE INTO rooms (room_number, name, type, capacity, price_per_night, description, amenities, is_available) VALUES
('R101', 'Maasai Standard', 'standard', 2, 5000.00, 'Comfortable room with modern amenities and traditional Maasai decor.', '["WiFi","TV","Air Conditioning","Hot Shower","Mini Fridge"]', 1),
('R102', 'Savanna Deluxe', 'deluxe', 2, 8500.00, 'Spacious deluxe room with panoramic views of the surrounding landscape.', '["WiFi","TV","Air Conditioning","Hot Shower","Mini Bar","Balcony","Room Service"]', 1),
('R201', 'Enkaji Suite', 'suite', 4, 15000.00, 'Luxurious suite with separate living area and premium amenities.', '["WiFi","Smart TV","Climate Control","Jacuzzi","Mini Bar","Balcony","Room Service","Safe"]', 1),
('R202', 'Pool Villa', 'pool_villa', 2, 22000.00, 'Private villa with direct pool access and exclusive outdoor space.', '["WiFi","Smart TV","Climate Control","Private Pool","Mini Bar","Terrace","Butler Service","Safe","Kitchenette"]', 1),
('R301', 'Honeymoon Retreat', 'honeymoon', 2, 18000.00, 'Romantic honeymoon suite with rose petal turndown service and champagne.', '["WiFi","Smart TV","Climate Control","Jacuzzi","Mini Bar","Private Balcony","Spa Access","Champagne","Rose Petals"]', 1);

-- ━━ SAMPLE HALLS ━━
INSERT IGNORE INTO halls (hall_number, name, capacity, price_full_day, price_half_day, price_evening, description, features) VALUES
(1, 'Olkeri Hall', 200, 80000.00, 45000.00, 55000.00, 'Grand hall for large events, weddings, and conferences.', '["Stage","Sound System","Projector","WiFi","Air Conditioning","Catering Kitchen","Dance Floor","Parking"]'),
(2, 'Enkaji Conference Room', 50, 35000.00, 20000.00, 25000.00, 'Modern conference room ideal for corporate meetings and workshops.', '["Projector","Whiteboard","WiFi","Air Conditioning","Coffee Station","Flipcharts"]'),
(3, 'Garden Pavilion', 150, 60000.00, 35000.00, 45000.00, 'Open-air pavilion surrounded by gardens, perfect for outdoor celebrations.', '["Tent Cover","Lighting","Sound System","Dance Floor","BBQ Area","Parking"]');

-- ━━ SAMPLE MENU ITEMS ━━
INSERT IGNORE INTO menu_items (category_id, name, description, price, is_vegetarian, is_spicy, sort_order) VALUES
(1, 'Full English Breakfast', 'Eggs, bacon, sausage, toast, beans, and fresh juice', 850.00, 0, 0, 1),
(1, 'Maasai Chai & Mandazi', 'Traditional tea with fresh mandazi doughnuts', 350.00, 1, 0, 2),
(1, 'Fresh Fruit Platter', 'Seasonal tropical fruits with yogurt and honey', 550.00, 1, 0, 3),
(2, 'Nyama Choma Platter', 'Grilled meat served with ugali and sukuma wiki', 1200.00, 0, 0, 1),
(2, 'Grilled Tilapia', 'Lake Victoria tilapia with coconut rice', 950.00, 0, 0, 2),
(2, 'Garden Fresh Salad', 'Mixed greens with avocado and vinaigrette', 650.00, 1, 0, 3),
(3, 'Pan-Seared Lamb Chops', 'Herb-crusted lamb with root vegetables', 2200.00, 0, 0, 1),
(3, 'Grilled Lobster Tail', 'Butter-poached lobster with garlic risotto', 3500.00, 0, 0, 2),
(3, 'Mushroom Risotto', 'Creamy arborio rice with wild mushrooms and parmesan', 1400.00, 1, 0, 3),
(4, 'Samosa Trio', 'Beef, chicken, and vegetable samosas with tamarind chutney', 450.00, 0, 1, 1),
(4, 'Bruschetta', 'Toasted bread with tomato, basil and olive oil', 550.00, 1, 0, 2),
(5, 'Beef Steak', 'Premium cut grilled to your preference with sides', 2500.00, 0, 0, 1),
(5, 'Chicken Tikka Masala', 'Tender chicken in rich tomato curry', 1300.00, 0, 1, 2),
(6, 'Chocolate Lava Cake', 'Warm chocolate cake with vanilla ice cream', 750.00, 1, 0, 1),
(6, 'Crème Brûlée', 'Classic vanilla custard with caramelized sugar', 650.00, 1, 0, 2),
(7, 'Fresh Mango Juice', 'Freshly squeezed mango juice', 300.00, 1, 0, 1),
(7, 'Tusker Lager', 'Kenya''s finest beer, ice cold', 400.00, 1, 0, 2),
(7, 'Espresso', 'Single shot Kenyan AA espresso', 250.00, 1, 0, 3);

-- ━━ SAMPLE TESTIMONIALS ━━
INSERT IGNORE INTO testimonials (guest_name, rating, message, source, is_approved, is_featured) VALUES
('James & Mary Thompson', 5, 'Our wedding at Olkeri Hall was absolutely magical. The staff went above and beyond to make our special day perfect. The food was incredible and the setting was breathtaking.', 'google', 1, 1),
('Dr. Sarah Kimani', 5, 'A hidden gem in Narok County. The pool villa was paradise — private, luxurious, and so peaceful. We will definitely return for our anniversary.', 'tripadvisor', 1, 1),
('Michael O''Brien', 4, 'Excellent conference facilities. We hosted a 3-day corporate retreat here and everything was flawlessly organized. The enkaji conference room had all the tech we needed.', 'website', 1, 1),
('Amina Hassan', 5, 'The spa treatments were heavenly! The Maasai-inspired wellness rituals are truly unique. Combined with the stunning location, this is the ultimate relaxation destination.', 'booking', 1, 0),
('David & Lisa Chen', 5, 'We chose Sidai for our honeymoon and it exceeded all expectations. The honeymoon suite was gorgeous, the dining was world-class, and the staff made us feel so special.', 'google', 1, 0);

-- Done!
