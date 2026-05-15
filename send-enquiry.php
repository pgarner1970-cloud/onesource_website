<?php
require_once __DIR__ . '/site-config.php';

function clean_input($value) {
    return trim(str_replace(["\r", "\n"], ' ', $value ?? ''));
}

function fail($message) {
    header('Location: contact.php?status=error&message=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// Honeypot anti-spam field. Real users will never fill this.
if (!empty($_POST['website'])) {
    fail('Spam check failed.');
}

$name = clean_input($_POST['name'] ?? '');
$phone = clean_input($_POST['phone'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$service = clean_input($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $phone === '' || $email === '' || $message === '') {
    fail('Please complete all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail('Please enter a valid email address.');
}



$subject = $ENQUIRY_SUBJECT_PREFIX . ': ' . $service;

$body = "New website enquiry\n\n";
$body .= "Name: {$name}\n";
$body .= "Phone: {$phone}\n";
$body .= "Email: {$email}\n";
$body .= "Service: {$service}\n\n";
$body .= "Message:\n{$message}\n\n";
$body .= "Sent from: " . ($_SERVER['HTTP_HOST'] ?? 'website') . "\n";

$headers = [];
$headers[] = 'From: One Source Website <' . $ENQUIRY_FROM_EMAIL . '>';
$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$sent = mail($ENQUIRY_TO_EMAIL, $subject, $body, implode("\r\n", $headers));

if (!$sent) {
    fail('The enquiry could not be sent. Please phone or email us directly.');
}

// Optional local enquiry log
$logDir = __DIR__ . '/data';
$logFile = $logDir . '/enquiries.json';

if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$enquiries = [];
if (file_exists($logFile)) {
    $enquiries = json_decode(file_get_contents($logFile), true);
    if (!is_array($enquiries)) {
        $enquiries = [];
    }
}

$enquiries[] = [
    'date' => date('c'),
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'service' => $service,
    'message' => $message
];

file_put_contents($logFile, json_encode($enquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: contact.php?status=success');
exit;
?>