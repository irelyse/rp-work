<?php
require_once 'backend/db.php';

$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($fullname) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO support_messages (fullname, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$fullname, $email, $subject, $message]);
            $success = true;
        } catch (PDOException $e) {
            $error = "Error saving message: " . $e->getMessage();
        }
    }
}
?>

<?php if ($success): ?>
<div style="background: #10B981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
    <i data-lucide="check-circle" size="20"></i>
    <span>Message sent successfully! Our support team will get back to you soon.</span>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div style="background: #EF4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
    <i data-lucide="alert-circle" size="20"></i>
    <span><?php echo $error; ?></span>
</div>
<?php endif; ?>

<div class="page-header">
    <div class="page-title-group">
        <h1 class="page-title">Contact Support</h1>
        <p class="page-subtitle">Need assistance with routes or payments? Reach out to our dedicated support team today.</p>
    </div>
</div>

<div class="support-container" style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem; margin-top: 1.5rem;">
    <!-- Contact Information Cards -->
    <div class="support-info">
        <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background: rgba(230, 49, 151, 0.1); color: #E63197; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="phone" size="20"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1F2937;">Phone Support</h3>
                    <p style="font-size: 0.9rem; color: #6B7280;">Mon - Fri, 8am - 5pm</p>
                </div>
            </div>
            <p style="font-size: 1.1rem; font-weight: 500; color: #111827; margin-left: 3.5rem;">+250 788 123 456</p>
        </div>

        <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background: rgba(14, 165, 233, 0.1); color: #0EA5E9; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="mail" size="20"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1F2937;">Email Support</h3>
                    <p style="font-size: 0.9rem; color: #6B7280;">Response within 24 hours</p>
                </div>
            </div>
            <p style="font-size: 1.1rem; font-weight: 500; color: #111827; margin-left: 3.5rem;">support@classicacademy.rw</p>
        </div>

        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background: rgba(34, 197, 94, 0.1); color: #22C55E; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="map-pin" size="20"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1F2937;">Office Location</h3>
                    <p style="font-size: 0.9rem; color: #6B7280;">Visit us for on-site support</p>
                </div>
            </div>
            <p style="font-size: 1rem; color: #111827; margin-left: 3.5rem; line-height: 1.5;">Kigali Innovation City,<br>Plot 45, Nyarugenge District,<br>Kigali, Rwanda</p>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="support-form">
        <div class="card" style="padding: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: #1F2937; margin-bottom: 0.5rem;">Send us a message</h2>
            <p style="font-size: 0.95rem; color: #6B7280; margin-bottom: 2rem;">Have a specific question or technical issue? Fill out the form below and our team will get back to you.</p>

            <form action="" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Full Name</label>
                        <input type="text" name="fullname" placeholder="John Doe" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #E5E7EB; border-radius: 8px; font-family: 'Outfit'; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#E63197'">
                    </div>
                    <div class="form-group">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Email Address</label>
                        <input type="email" name="email" placeholder="john@example.com" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #E5E7EB; border-radius: 8px; font-family: 'Outfit'; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#E63197'">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Subject</label>
                    <select name="subject" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #E5E7EB; border-radius: 8px; font-family: 'Outfit'; outline: none; background: white;">
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Technical Issue">Technical Issue</option>
                        <option value="Billing Question">Billing Question</option>
                        <option value="Route Update Request">Route Update Request</option>
                        <option value="Feature Request">Feature Request</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Message</label>
                    <textarea name="message" rows="5" placeholder="How can we help you?" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #E5E7EB; border-radius: 8px; font-family: 'Outfit'; outline: none; resize: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#E63197'"></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 600; border-radius: 8px; cursor: pointer; border: none; background: #E63197; color: white; transition: background 0.2s;" onmouseover="this.style.background='#D42C8B'" onmouseout="this.style.background='#E63197'">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons if not already done
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
