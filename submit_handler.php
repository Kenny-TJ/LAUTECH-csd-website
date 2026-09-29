<?php
// submit_handler.php — processes form submission, never visited directly

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /lautech_csd/profile.php');
    exit;
}

function redirectError($code) {
    header("Location: /lautech_csd/profile.php?error=$code");
    exit;
}

// 1. Collect and sanitise
$matric_no    = strtoupper(trim(isset($_POST['matric_no'])    ? $_POST['matric_no']    : ''));
$full_name    = ucwords(strtolower(trim(isset($_POST['full_name'])    ? $_POST['full_name']    : '')));
$level        = intval(isset($_POST['level'])        ? $_POST['level']        : 0);
$session_year = trim(isset($_POST['session_year']) ? $_POST['session_year'] : '');
$email        = strtolower(trim(isset($_POST['email'])        ? $_POST['email']        : ''));
$phone        = trim(isset($_POST['phone'])        ? $_POST['phone']        : '');

// 2. Required fields
if (!$matric_no || !$full_name || !$level || !$session_year || !$email) {
    redirectError('empty_fields');
}

// 3. Matric number format: e.g. CSE/2021/001
if (!preg_match('/^[A-Z]{2,5}\/\d{4}\/\d{2,4}$/', $matric_no)) {
    redirectError('invalid_matric');
}

// 4. Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectError('invalid_email');
}

// 5. Level validation
if (!in_array($level, array(100, 200, 300, 400, 500))) {
    redirectError('empty_fields');
}

// 6. File present?
if (!isset($_FILES['student_file']) || $_FILES['student_file']['error'] === UPLOAD_ERR_NO_FILE) {
    redirectError('no_file');
}

$file    = $_FILES['student_file'];
$maxSize = 2 * 1024 * 1024; // 2MB
$allowed = array('pdf' => 'application/pdf',
                 'jpg' => 'image/jpeg',
                 'png' => 'image/png');

// 7. Upload error check
if ($file['error'] !== UPLOAD_ERR_OK) {
    redirectError('upload_failed');
}

// 8. File size
if ($file['size'] > $maxSize) {
    redirectError('file_too_large');
}

// 9. MIME type check (with fallback for servers where finfo is disabled)
$fileExt = false;
if (function_exists('finfo_open')) {
    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $fileExt  = array_search($mimeType, $allowed, true);
} else {
    // Fallback: use extension only
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext === 'jpeg') { $ext = 'jpg'; }
    if (array_key_exists($ext, $allowed)) { $fileExt = $ext; }
}

if ($fileExt === false) {
    redirectError('invalid_type');
}

// 10. Generate safe filename and move file
$storedName = preg_replace('/[^A-Za-z0-9]/', '_', $matric_no) . '_' . time() . '.' . $fileExt;
$uploadDir  = dirname(__FILE__) . '/uploads/';
$uploadPath = $uploadDir . $storedName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    redirectError('upload_failed');
}

// 11. Duplicate matric check
$stmt = mysqli_prepare($conn, "SELECT id FROM students WHERE matric_no = ?");
mysqli_stmt_bind_param($stmt, 's', $matric_no);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    unlink($uploadPath);
    redirectError('duplicate_matric');
}
mysqli_stmt_close($stmt);

// 12. Insert student
$stmt = mysqli_prepare($conn,
    "INSERT INTO students (matric_no, full_name, level, email, phone, session_year)
     VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'ssisss',
    $matric_no, $full_name, $level, $email, $phone, $session_year);
if (!mysqli_stmt_execute($stmt)) {
    unlink($uploadPath);
    redirectError('db_error');
}
$studentId = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// 13. Insert submission
$stmt = mysqli_prepare($conn,
    "INSERT INTO submissions (student_id, file_name, file_path, file_type, file_size)
     VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'isssi',
    $studentId, $file['name'], $storedName, $fileExt, $file['size']);
if (!mysqli_stmt_execute($stmt)) {
    redirectError('db_error');
}
mysqli_stmt_close($stmt);

// 14. Success
header("Location: /lautech_csd/verify.php?id=$studentId");
exit;
