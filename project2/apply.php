<?php
// Bắt đầu session nếu cần
session_start();

// Pull validation errors and old inputs (if any) then clear them
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
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
                        <option value="NA101" <?= (isset($old['jobref']) && $old['jobref'] === 'NA101') ? 'selected' : '' ?>>NA101 - Network Administrator</option>
                        <option value="BE204" <?= (isset($old['jobref']) && $old['jobref'] === 'BE204') ? 'selected' : '' ?>>BE204 - Back-End Developer</option>
                        <option value="UX307" <?= (isset($old['jobref']) && $old['jobref'] === 'UX307') ? 'selected' : '' ?>>UX307 - UI/UX Designer</option>
                        <option value="ML451" <?= (isset($old['jobref']) && $old['jobref'] === 'ML451') ? 'selected' : '' ?>>ML451 - Machine Learning Engineer</option>
                    </select>
                    <?php if (isset($errors['jobref'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['jobref']) ?></div>
                    <?php endif; ?>
                </fieldset>

                <!-- Personal Info -->
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <!-- <input type="text" id="fname" name="fname" maxlength="20" pattern="[A-Za-z]{1,20}" required> -->
                        <input type="text" id="fname" name="fname" required value="<?= htmlspecialchars($old['fname'] ?? '') ?>">
                        <?php if (isset($errors['fname'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['fname']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" maxlength="20" pattern="[A-Za-z]{1,20}" required value="<?= htmlspecialchars($old['lname'] ?? '') ?>">
                        <?php if (isset($errors['lname'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['lname']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required value="<?= isset($old['dob']) ? htmlspecialchars($old['dob']) : '' ?>">
                        <?php if (isset($errors['dob'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['dob']) ?></div>
                        <?php endif; ?>
                    </div>
                    <fieldset class="gender-group">
                        <legend>Gender</legend>
                        <label><input type="radio" name="gender" value="Male" required <?= (isset($old['gender']) && $old['gender'] === 'Male') ? 'checked' : '' ?>> Male</label>
                        <label><input type="radio" name="gender" value="Female" <?= (isset($old['gender']) && $old['gender'] === 'Female') ? 'checked' : '' ?>> Female</label>
                        <label><input type="radio" name="gender" value="Other" <?= (isset($old['gender']) && $old['gender'] === 'Other') ? 'checked' : '' ?>> Other</label>
                        <?php if (isset($errors['gender'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['gender']) ?></div>
                        <?php endif; ?>
                    </fieldset>
                </fieldset>

                <!-- Contact Info -->
                <fieldset>
                    <legend>Contact Information</legend>
                    <div class="form-group">
                        <label for="street">Street Address:</label>
                        <input type="text" id="street" name="street" maxlength="40" required value="<?= htmlspecialchars($old['street'] ?? '') ?>">
                        <?php if (isset($errors['street'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['street']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="suburb">Suburb/Town:</label>
                        <input type="text" id="suburb" name="suburb" maxlength="40" required value="<?= htmlspecialchars($old['suburb'] ?? '') ?>">
                        <?php if (isset($errors['suburb'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['suburb']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="state">State:</label>
                        <select id="state" name="state" required>
                            <option value="">-- Select State --</option>
                            <?php foreach (['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'] as $st): ?>
                                <option value="<?= $st ?>" <?= (isset($old['state']) && $old['state'] === $st) ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['state'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['state']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="postcode">Postcode:</label>
                        <input type="text" id="postcode" name="postcode" pattern="\d{4}" maxlength="4" required value="<?= htmlspecialchars($old['postcode'] ?? '') ?>">
                        <?php if (isset($errors['postcode'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['postcode']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                        <?php if (isset($errors['email'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" pattern="[\d\s]{8,12}" required value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                        <?php if (isset($errors['phone'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['phone']) ?></div>
                        <?php endif; ?>
                    </div>
                </fieldset>

                <!-- Skills -->
                <fieldset>
                    <legend>Technical Skills</legend>
                    <div class="checkbox-group">
                        <?php $old_skills = $old['skills'] ?? []; ?>
                        <label><input type="checkbox" name="skills[]" value="HTML" <?= in_array('HTML', $old_skills) ? 'checked' : '' ?>> HTML</label>
                        <label><input type="checkbox" name="skills[]" value="CSS" <?= in_array('CSS', $old_skills) ? 'checked' : '' ?>> CSS</label>
                        <label><input type="checkbox" name="skills[]" value="JavaScript" <?= in_array('JavaScript', $old_skills) ? 'checked' : '' ?>> JavaScript</label>
                        <label><input type="checkbox" name="skills[]" value="Python" <?= in_array('Python', $old_skills) ? 'checked' : '' ?>> Python</label>
                    </div>
                    <?php if (isset($errors['skills'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['skills']) ?></div>
                    <?php endif; ?>
                    <label for="others">Other Skills:</label>
                    <textarea id="others" name="others" rows="4" placeholder="Write about your other skills..."><?= htmlspecialchars($old['others'] ?? '') ?></textarea>
                    <?php if (isset($errors['others'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['others']) ?></div>
                    <?php endif; ?>
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