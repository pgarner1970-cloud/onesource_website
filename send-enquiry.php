<?php
require_once __DIR__ . '/includes/content-store.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');
$honeypot = trim($_POST['website'] ?? '');

if ($honeypot !== '') {
    header('Location: contact.php?sent=1');
    exit;
}

if ($name === '' || $message === '' || ($email === '' && $phone === '')) {
    header('Location: contact.php?error=1');
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.php?error=1');
    exit;
}

$enquiryId = null;
try {
    $enquiryId = create_enquiry([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'service' => $service,
        'message' => $message,
        'status' => 'new'
    ]);
} catch (Throwable $e) {}

$notifyEmail = setting_get('enquiry_notify_email', 'luke@onesourceairandenergyltd.co.uk');
$emailEnabled = setting_get('enquiry_email_notifications', '1') === '1';

if ($emailEnabled && $notifyEmail !== '') {
    $subject = 'New website enquiry' . ($service ? ' - ' . $service : '');
    $body = "New website enquiry\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nService: {$service}\nMessage:\n{$message}\n";
    if ($enquiryId) { $body .= "\nAdmin enquiry ID: {$enquiryId}\n"; }
    $headers = "From: One Source Website <no-reply@onesourceairandenergyltd.co.uk>\r\n";
    if ($email !== '') { $headers .= "Reply-To: {$email}\r\n"; }
    @mail($notifyEmail, $subject, $body, $headers);
}

header('Location: contact.php?sent=1');
exit;
?>