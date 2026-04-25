<?php

require_once __DIR__ . '/app/includes/init.php';
use App\Core\Database;

try {
    $db = Database::getInstance();
    
    // Clear existing
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    $db->query("TRUNCATE TABLE menu_items");
    $db->query("TRUNCATE TABLE menu_categories");
    $db->query("SET FOREIGN_KEY_CHECKS = 1");

    // Insert categories
    $categories = [
        'Foods' => 'A variety of delicious meals to satisfy your hunger.',
        'Drinks' => 'Refreshing beverages to quench your thirst.',
        'Dining' => 'Premium dining experiences for special occasions.',
        'Mbuzi Choma & Special Request' => 'Signature roasted goat and tailored special requests.'
    ];

    $catIds = [];
    foreach ($categories as $name => $desc) {
        $db->query("INSERT INTO menu_categories (name, description) VALUES (?, ?)", [$name, $desc]);
        $catIds[$name] = $db->lastInsertId();
    }

    // Insert items
    $items = [
        // Foods (200 - 800)
        ['category' => 'Foods', 'name' => 'Fries & Sausage', 'price' => 200, 'desc' => 'Crispy fries with beef sausage'],
        ['category' => 'Foods', 'name' => 'Chicken Burger', 'price' => 450, 'desc' => 'Grilled chicken burger with side fries'],
        ['category' => 'Foods', 'name' => 'Beef Stew with Ugali', 'price' => 350, 'desc' => 'Local beef stew with traditional ugali'],
        ['category' => 'Foods', 'name' => 'Grilled Tilapia', 'price' => 800, 'desc' => 'Fresh tilapia grilled with lemon herbs'],

        // Drinks (200 - 500)
        ['category' => 'Drinks', 'name' => 'Fresh Juice', 'price' => 200, 'desc' => 'Mango, passion, or tropical blend'],
        ['category' => 'Drinks', 'name' => 'Soda / Water', 'price' => 200, 'desc' => 'Assorted sodas and mineral water'],
        ['category' => 'Drinks', 'name' => 'Signature Mocktail', 'price' => 350, 'desc' => 'Refreshing fruit mocktail'],
        ['category' => 'Drinks', 'name' => 'Local Beers', 'price' => 250, 'desc' => 'Assorted local Kenyan beers'],

        // Dining (800 - 1400)
        ['category' => 'Dining', 'name' => 'Couples Dinner Platter', 'price' => 1400, 'desc' => 'Assorted meats and sides for two'],
        ['category' => 'Dining', 'name' => 'Executive Lunch Buffet', 'price' => 1200, 'desc' => 'Full access to our lunch buffet'],
        ['category' => 'Dining', 'name' => 'Savanna Flame Steak', 'price' => 1400, 'desc' => 'Premium beef steak with gourmet sides'],

        // Mbuzi Choma & Special Request (1000 - 1400)
        ['category' => 'Mbuzi Choma & Special Request', 'name' => '1/4 Kg Mbuzi Choma', 'price' => 400, 'desc' => 'Roasted goat meat'],
        ['category' => 'Mbuzi Choma & Special Request', 'name' => '1/2 Kg Mbuzi Choma', 'price' => 800, 'desc' => 'Roasted goat meat with kachumbari'],
        ['category' => 'Mbuzi Choma & Special Request', 'name' => '1 Kg Mbuzi Choma', 'price' => 1400, 'desc' => 'Full kg roasted goat meat with all sides'],
        ['category' => 'Mbuzi Choma & Special Request', 'name' => 'Chef\'s Special Request', 'price' => 1200, 'desc' => 'Custom meal prepared by our head chef'],
    ];

    foreach ($items as $item) {
        $db->query(
            "INSERT INTO menu_items (category_id, name, description, price, is_available) VALUES (?, ?, ?, ?, 1)",
            [$catIds[$item['category']], $item['name'], $item['desc'], $item['price']]
        );
    }

    echo "Menu seeded successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
