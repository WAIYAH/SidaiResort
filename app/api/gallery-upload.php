<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Check if user is admin
    if (!isset($_SESSION['staff'])) {
        throw new Exception('Unauthorized');
    }

    // Validate CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        throw new Exception('Invalid CSRF token');
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload failed');
    }

    $file = $_FILES['image'];
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    // Validate file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimes)) {
        throw new Exception('Invalid file type. Only JPEG, PNG, WebP, and GIF are allowed.');
    }

    if ($file['size'] > $maxSize) {
        throw new Exception('File size exceeds 5MB limit');
    }

    // Create upload directory if needed
    $uploadDir = STORAGE_PATH . '/uploads/gallery/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'gallery-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to save file');
    }

    // Create thumbnail
    $thumbnail = create_thumbnail($filepath, $uploadDir, 'thumb-' . $filename);

    // Save to database
    $db = \App\Core\Database::getInstance();
    $db->query(
        'INSERT INTO gallery_items (title, description, image_path, image_thumb, category, uploaded_by)
         VALUES (:title, :description, :path, :thumb, :category, :user_id)',
        [
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':path' => '/uploads/gallery/' . $filename,
            ':thumb' => $thumbnail ? '/uploads/gallery/' . $thumbnail : null,
            ':category' => $_POST['category'] ?? 'other',
            ':user_id' => $_SESSION['staff']['id'],
        ]
    );

    log_audit_action('gallery.upload', 'gallery_item', null, null, ['filename' => $filename]);

    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded successfully',
        'filename' => $filename,
        'path' => '/uploads/gallery/' . $filename,
    ]);

} catch (Exception $exception) {
    log_error('Gallery upload failed', $exception);

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage()
    ]);
    exit(1);
}

function create_thumbnail(string $source, string $destDir, string $destName, int $maxWidth = 300, int $maxHeight = 300): ?string
{
    try {
        $info = getimagesize($source);
        if (!$info) {
            return null;
        }

        list($width, $height, $type) = $info;

        // Calculate thumbnail dimensions
        $ratio = $width / $height;
        if ($maxWidth / $maxHeight > $ratio) {
            $maxWidth = (int)($maxHeight * $ratio);
        } else {
            $maxHeight = (int)($maxWidth / $ratio);
        }

        // Create thumbnail image
        $thumb = imagecreatetruecolor($maxWidth, $maxHeight);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $source_img = imagecreatefromjpeg($source);
                imagecopyresampled($thumb, $source_img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
                imagejpeg($thumb, $destDir . $destName, 90);
                break;
            case IMAGETYPE_PNG:
                $source_img = imagecreatefrompng($source);
                imagecopyresampled($thumb, $source_img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
                imagepng($thumb, $destDir . $destName);
                break;
            default:
                return null;
        }

        imagedestroy($thumb);
        return $destName;
    } catch (Throwable $exception) {
        return null;
    }
}
