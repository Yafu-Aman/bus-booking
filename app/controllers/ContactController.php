<?php

class ContactController extends Controller {

    public function showContact() {
        $this->render('contact');
    }

    public function handleContact() {
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation
        if (empty($name) || empty($email) || 
            empty($subject) || empty($message)) {
            $this->render('contact', [
                'error' => 'Please fill in all fields.'
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('contact', [
                'error' => 'Please enter a valid email address.'
            ]);
            return;
        }

        if (strlen($message) < 10) {
            $this->render('contact', [
                'error' => 'Message must be at least 10 characters.'
            ]);
            return;
        }

        // Save to database
        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO contact_messages (name, email, subject, message)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param('ssss', $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $this->render('contact', [
                'success' => 'Your message has been sent successfully. We will get back to you soon!'
            ]);
        } else {
            $this->render('contact', [
                'error' => 'Something went wrong. Please try again.'
            ]);
        }
    }
}