<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Models\Room;
use App\Models\Hall;

$pageTitle = 'Book Now';
$pageDescription = 'Make your reservation at Sidai Safari Dreams';

$roomModel = new Room();
$hallModel = new Hall();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
    <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/assets/css/forms.css">
</head>
<body>
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="min-h-screen bg-cream pt-20 pb-10">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-playfair font-bold text-forest-green mb-4">Make a Booking</h1>
                <p class="text-lg text-gray-600">Choose your perfect getaway at Sidai Safari Dreams</p>
            </div>

            <form id="bookingForm" method="POST" action="<?php echo WEB_ROOT; ?>/api/booking-submit.php" class="bg-white rounded-lg shadow-lg p-8">
                <?php echo csrf_token_field(); ?>

                <!-- Booking Type -->
                <div class="mb-6">
                    <label for="booking_type" class="block text-sm font-semibold text-forest-green mb-2">What are you booking?</label>
                    <select id="booking_type" name="booking_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        <option value="">Select booking type</option>
                        <option value="room">Room/Accommodation</option>
                        <option value="hall">Hall/Event Space</option>
                        <option value="pool">Pool Party</option>
                        <option value="dining">Dining Experience</option>
                        <option value="event">Event Planning</option>
                    </select>
                </div>

                <!-- Guest Information -->
                <fieldset class="mb-8 p-6 border-2 border-gold-light rounded-lg">
                    <legend class="text-lg font-semibold text-forest-green mb-4">Your Information</legend>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-forest-green mb-2">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-forest-green mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-forest-green mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>

                        <div>
                            <label for="num_guests" class="block text-sm font-semibold text-forest-green mb-2">Number of Guests</label>
                            <input type="number" id="num_guests" name="num_guests" min="1" value="1" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>
                    </div>
                </fieldset>

                <!-- Room Booking -->
                <div id="roomSection" class="hidden mb-8 p-6 border-2 border-gold-light rounded-lg">
                    <legend class="text-lg font-semibold text-forest-green mb-4">Room Details</legend>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="check_in" class="block text-sm font-semibold text-forest-green mb-2">Check-in Date</label>
                            <input type="date" id="check_in" name="check_in"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>

                        <div>
                            <label for="check_out" class="block text-sm font-semibold text-forest-green mb-2">Check-out Date</label>
                            <input type="date" id="check_out" name="check_out"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>

                        <div class="md:col-span-2">
                            <label for="room_id" class="block text-sm font-semibold text-forest-green mb-2">Select Room</label>
                            <select id="room_id" name="room_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                                <option value="">Choose a room...</option>
                                <?php foreach ($roomModel->getAll() as $room): ?>
                                    <option value="<?php echo $room['id']; ?>">
                                        <?php echo htmlspecialchars($room['name']); ?> - KES <?php echo format_kes($room['price_per_night']); ?>/night
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Hall Booking -->
                <div id="hallSection" class="hidden mb-8 p-6 border-2 border-gold-light rounded-lg">
                    <legend class="text-lg font-semibold text-forest-green mb-4">Hall Details</legend>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="event_date" class="block text-sm font-semibold text-forest-green mb-2">Event Date</label>
                            <input type="date" id="event_date" name="event_date"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        </div>

                        <div>
                            <label for="hall_id" class="block text-sm font-semibold text-forest-green mb-2">Select Hall</label>
                            <select id="hall_id" name="hall_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                                <option value="">Choose a hall...</option>
                                <?php foreach ($hallModel->getAll() as $hall): ?>
                                    <option value="<?php echo $hall['id']; ?>">
                                        <?php echo htmlspecialchars($hall['name']); ?> (<?php echo $hall['capacity']; ?> guests)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="event_type" class="block text-sm font-semibold text-forest-green mb-2">Event Type</label>
                            <select id="event_type" name="event_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                                <option value="">Select event type</option>
                                <option value="wedding">Wedding</option>
                                <option value="conference">Conference</option>
                                <option value="party">Party</option>
                                <option value="corporate">Corporate Event</option>
                            </select>
                        </div>

                        <div>
                            <label for="event_setup" class="block text-sm font-semibold text-forest-green mb-2">Setup Preference</label>
                            <select id="event_setup" name="event_setup"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                                <option value="banquet">Banquet</option>
                                <option value="theatre">Theatre</option>
                                <option value="classroom">Classroom</option>
                                <option value="cocktail">Cocktail</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="mb-6">
                    <label for="special_requests" class="block text-sm font-semibold text-forest-green mb-2">Special Requests (Optional)</label>
                    <textarea id="special_requests" name="special_requests" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold"
                              placeholder="Any special requests or dietary requirements?"></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gold hover:bg-gold-dark text-white font-semibold py-3 rounded-lg transition">
                        Continue to Payment
                    </button>
                    <a href="<?php echo WEB_ROOT; ?>" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg text-center transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const bookingTypeSelect = document.getElementById('booking_type');
        const roomSection = document.getElementById('roomSection');
        const hallSection = document.getElementById('hallSection');

        bookingTypeSelect.addEventListener('change', function() {
            roomSection.classList.add('hidden');
            hallSection.classList.add('hidden');

            if (this.value === 'room') {
                roomSection.classList.remove('hidden');
            } else if (this.value === 'hall') {
                hallSection.classList.remove('hidden');
            }
        });
    </script>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
