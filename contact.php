<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Contact Us</h2>
    <p>Fill out the details below to compose an email to our support team.</p>

    <form id="contactForm">
        <label>Your Name:</label>
        <input type="text" id="contactName" placeholder="Enter your full name" required>

        <label>Your Email Address:</label>
        <input type="email" id="contactEmail" placeholder="Enter your email" required>

        <label>Subject:</label>
        <input type="text" id="contactSubject" placeholder="What is this regarding?" required>

        <label>Describe the Problem:</label>
        <textarea id="contactProblem" rows="5" placeholder="Tell us more..." required></textarea>

        <button type="submit" class="btn">Open Email App</button>
    </form>
</div>

<?php include 'footer.php'; ?>