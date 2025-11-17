<?php
// Bắt đầu session nếu cần
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <?php include 'header.inc'; ?>

    <main class="apply-layout">
        <!-- Left: Application Form -->
        <section class="form-section">
            <h2>Job Application Form</h2>
            <p>Please fill out the form below to apply for a position with us.</p>

            <form action="./process_eoi.php" method="post" novalidate="novalidate">
                <!-- Job Details -->
                <fieldset>
                    <legend>Job Details</legend>
                    <label for=" jobref">Job Reference Number:</label>
                    <select id="jobref" name="jobref" required>
                        <option value="">-- Select Job --</option>
                        <option value="NA101">NA101 - Network Administrator</option>
                        <option value="BE204">BE204 - Back-End Developer</option>
                        <option value="UX307">UX307 - UI/UX Designer</option>
                        <option value="ML451">ML451 - Machine Learning Engineer</option>
                    </select>
                </fieldset>

                <!-- Personal Info -->
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <!-- <input type="text" id="fname" name="fname" maxlength="20" pattern="[A-Za-z]{1,20}" required> -->
                        <input type="text" id="fname" name="fname" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" maxlength="20" pattern="[A-Za-z]{1,20}" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <fieldset class="gender-group">
                        <legend>Gender</legend>
                        <label><input type="radio" name="gender" value="Male" required> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                        <label><input type="radio" name="gender" value="Other"> Other</label>
                    </fieldset>
                </fieldset>

                <!-- Contact Info -->
                <fieldset>
                    <legend>Contact Information</legend>
                    <div class="form-group">
                        <label for="street">Street Address:</label>
                        <input type="text" id="street" name="street" maxlength="40" required>
                    </div>
                    <div class="form-group">
                        <label for="suburb">Suburb/Town:</label>
                        <input type="text" id="suburb" name="suburb" maxlength="40" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State:</label>
                        <select id="state" name="state" required>
                            <option value="">-- Select State --</option>
                            <option>VIC</option>
                            <option>NSW</option>
                            <option>QLD</option>
                            <option>NT</option>
                            <option>WA</option>
                            <option>SA</option>
                            <option>TAS</option>
                            <option>ACT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="postcode">Postcode:</label>
                        <input type="text" id="postcode" name="postcode" pattern="\d{4}" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" pattern="[\d\s]{8,12}" required>
                    </div>
                </fieldset>

                <!-- Skills -->
                <fieldset>
                    <legend>Technical Skills</legend>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="skills[]" value="HTML" checked> HTML</label>
                        <label><input type="checkbox" name="skills[]" value="CSS"> CSS</label>
                        <label><input type="checkbox" name="skills[]" value="JavaScript"> JavaScript</label>
                        <label><input type="checkbox" name="skills[]" value="Python"> Python</label>
                    </div>
                    <label for="others">Other Skills:</label>
                    <textarea id="others" name="others" rows="4"
                        placeholder="Write about your other skills..."></textarea>
                </fieldset>

                <!-- Submit -->
                <div class="form-submit">
                    <input type="submit" value="Apply">
                </div>
            </form>
        </section>

        <!-- Right: Sidebar -->
        <aside class="apply-sidebar" aria-labelledby="tips-title">
            <h2 id="tips-title">Application Tips</h2>
            <ul>
                <li>Double-check that your contact details are correct.</li>
                <li>Attach your updated résumé or portfolio link.</li>
                <li>Ensure the reference number matches your desired job.</li>
                <li>Highlight your most relevant skills and experience.</li>
            </ul>

            <h3>Why Join SmartTech?</h3>
            <p>We encourage innovation, teamwork, and lifelong learning.
                Join us to shape the future of technology together.</p>

            <h3>Contact HR</h3>
            <p>
                Email: <a href="mailto:info@smarttech.com.au">info@smarttech.com.au</a><br>
                Phone: (03) 9000 1234
            </p>
        </aside>
    </main>
    <?php include 'footer.inc'; ?>
</body>

</html>