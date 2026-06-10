<?php
$error   = $error   ?? '';
$success = $success ?? '';
?>

<div class="contact-container">

    <div class="contact-header">
        <h2>Contact Us</h2>
        <p>Have a question or need help? Send us a message and we will get back to you.</p>
    </div>

    <div class="contact-grid">

        <!-- Contact form -->
        <div class="contact-form-box">

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="/bus-booking/public/index.php?page=contact"
                  method="POST">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name"
                           placeholder="Your full name" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email"
                           placeholder="Your email address" required>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject" required>
                        <option value="" disabled selected>Select a subject</option>
                        <option value="Booking Issue">Booking Issue</option>
                        <option value="Payment Problem">Payment Problem</option>
                        <option value="Route Inquiry">Route Inquiry</option>
                        <option value="Cancellation Request">Cancellation Request</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Complaint">Complaint</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="5"
                              placeholder="Write your message here..."
                              required></textarea>
                </div>

                <button type="submit" class="btn-primary btn-full">
                    Send Message
                </button>

            </form>
        </div>

        <!-- Contact info -->
        <div class="contact-info-box">
            <h3>Get In Touch</h3>

            <div class="contact-info-item">
                <div class="contact-icon">📍</div>
                <div>
                    <strong>Address</strong>
                    <p>Meskel Square, Addis Ababa, Ethiopia</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">📞</div>
                <div>
                    <strong>Phone</strong>
                    <p>+251 911 000 000</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">📧</div>
                <div>
                    <strong>Email</strong>
                    <p>support@adeybusbooking.et</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">🕐</div>
                <div>
                    <strong>Working Hours</strong>
                    <p>Monday - Saturday: 8:00 AM - 6:00 PM</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">🚌</div>
                <div>
                    <strong>Terminal Support</strong>
                    <p>Available at all major bus terminals</p>
                </div>
            </div>
        </div>

    </div>
</div>