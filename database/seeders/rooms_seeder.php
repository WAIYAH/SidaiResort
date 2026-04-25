<?php
require_once dirname(__DIR__) . '/app/includes/init.php';
use App\Core\Database;

try {
    $db = Database::getInstance();
    
    // Clear existing rooms
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    $db->query("TRUNCATE TABLE rooms");
    $db->query("SET FOREIGN_KEY_CHECKS = 1");

    $rooms = [
        [
            'room_number' => 'EL-01',
            'name' => 'Elalai',
            'type' => 'suite',
            'capacity' => 4,
            'price_per_night' => 3200,
            'description' => "Elalai is where 'nothing but the best' stops being a promise and becomes a lived experience. Our most distinguished sanctuary.",
            'amenities' => json_encode(['Panoramic Forest View', 'King Super Bed', 'Private Seating Deck', 'Premium Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/1200/800/luxury,suite?random=100'])
        ],
        [
            'room_number' => 'EM-01',
            'name' => 'Emirishoi',
            'type' => 'deluxe',
            'capacity' => 2,
            'price_per_night' => 2000,
            'description' => "Step into Emirishoi and feel the morning claim you gently. This sanctuary wakes with the forest.",
            'amenities' => json_encode(['Forest View', 'King Bed', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=1'])
        ],
        [
            'room_number' => 'EN-01',
            'name' => 'Enchipai',
            'type' => 'standard',
            'capacity' => 2,
            'price_per_night' => 1200,
            'description' => "Enchipai wraps around you like the warmth of a fire you didn't know you needed. Rich earth tones mirror the landscape.",
            'amenities' => json_encode(['Garden View', 'Queen Bed', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=2'])
        ],
        [
            'room_number' => 'ES-01',
            'name' => 'Eserian',
            'type' => 'standard',
            'capacity' => 2,
            'price_per_night' => 1500,
            'description' => "Eserian is grace made tangible. The room moves with a soft elegance — clean lines softened by warm textures.",
            'amenities' => json_encode(['Canopy View', 'Double Bed', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=3'])
        ],
        [
            'room_number' => 'EM-02',
            'name' => 'Empiris',
            'type' => 'deluxe',
            'capacity' => 3,
            'price_per_night' => 2500,
            'description' => "There is something deeply grounding about Empiris. Like the ancient trees that surround it, this sanctuary stands firm.",
            'amenities' => json_encode(['Forest View', 'King Bed', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=4'])
        ],
        [
            'room_number' => 'EW-01',
            'name' => 'Ewangan',
            'type' => 'standard',
            'capacity' => 2,
            'price_per_night' => 1800,
            'description' => "Ewangan is a homecoming for people who have never been here before. It holds the particular magic of a place that feels familiar.",
            'amenities' => json_encode(['Nature View', 'Queen Bed', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=5'])
        ],
        [
            'room_number' => 'EK-01',
            'name' => 'Enkanasa',
            'type' => 'suite',
            'capacity' => 4,
            'price_per_night' => 1900,
            'description' => "Enkanasa holds an energy that encourages lingering. Morning coffee takes twice as long here — in the best possible way.",
            'amenities' => json_encode(['Courtyard View', 'Double Bed', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=6'])
        ],
        [
            'room_number' => 'EP-01',
            'name' => 'Esipil',
            'type' => 'standard',
            'capacity' => 2,
            'price_per_night' => 1400,
            'description' => "Named for the cool and flowing, Esipil carries the energy of a mountain stream — fresh, clear, and endlessly renewing.",
            'amenities' => json_encode(['Garden View', 'Twin Beds', 'Wi-Fi']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=7'])
        ],
        [
            'room_number' => 'EP-02',
            'name' => 'Enkipai',
            'type' => 'deluxe',
            'capacity' => 2,
            'price_per_night' => 2800,
            'description' => "Enkipai is, in the truest sense, a blessed space. There is a quality of light here — warm even on overcast days.",
            'amenities' => json_encode(['Forest View', 'King Bed', 'Balcony Access']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=8'])
        ],
        [
            'room_number' => 'ER-01',
            'name' => 'Eripoto',
            'type' => 'standard',
            'capacity' => 3,
            'price_per_night' => 1700,
            'description' => "Eripoto rewards those who pay attention. The deeper you look at the handcrafted headboard, the more beauty you find.",
            'amenities' => json_encode(['Nature View', 'Queen Bed', 'Artisan Décor']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=9'])
        ],
        [
            'room_number' => 'ER-02',
            'name' => 'Ereto',
            'type' => 'suite',
            'capacity' => 2,
            'price_per_night' => 3000,
            'description' => "There are rooms you sleep in, and then there is Ereto — a sanctuary engineered for the kind of rest that changes you.",
            'amenities' => json_encode(['Forest View', 'King Bed', 'Blackout Curtains']),
            'images' => json_encode(['https://loremflickr.com/800/600/luxury,bedroom?random=10'])
        ]
    ];

    foreach ($rooms as $room) {
        $db->query(
            "INSERT INTO rooms (room_number, name, type, capacity, price_per_night, description, amenities, images) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $room['room_number'], 
                $room['name'], 
                $room['type'], 
                $room['capacity'], 
                $room['price_per_night'], 
                $room['description'], 
                $room['amenities'], 
                $room['images']
            ]
        );
    }

    echo "Successfully seeded 11 rooms to match the frontend e-commerce data.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
