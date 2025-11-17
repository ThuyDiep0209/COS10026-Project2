<?php
session_start();

// Check login
if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
    header("Location: user_login.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="SmartTech Solutions - Home page introducing the company, services, and career opportunities.">
    <title>SmartTech Solutions | Home</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body class="home">

    <?php include 'header.inc'; ?>


    <main>

        <div>
            <p>Welcome, <?php echo htmlspecialchars($username); ?>
        </div>



        <section class="intro" aria-labelledby="intro-title">
            <div class="intro-text">
                <h2 id="intro-title">Building the Future of Technology</h2>
                <p>
                    At SmartTech Solutions, we transform ideas into innovation. Our
                    expert team delivers reliable, cutting-edge solutions that help
                    businesses grow and adapt in the digital era.
                </p>
            </div>
            <img src="images/teamwork.jpg" alt="SmartTech Solutions team collaborating in a modern office">
        </section>


        <section class="about" aria-labelledby="about-title">
            <h2 id="about-title">Who We Are</h2>
            <p>
                SmartTech Solutions is a Melbourne-based IT company specialising in
                network infrastructure, software development, and user experience
                design. Founded by a passionate team of engineers, our mission is to
                make technology accessible, efficient, and sustainable for every
                organisation.
            </p>
        </section>

        <section class="services" aria-labelledby="services-title">
            <h2 id="services-title">Our Areas of Expertise</h2>
            <ul>
                <li>
                    <strong>Network Administration</strong>
                    <p>Secure, high-performance networking solutions.</p>
                </li>
                <li>
                    <strong>Software Development</strong>
                    <p>Scalable, reliable applications built for growth.</p>
                </li>
                <li>
                    <strong>UI/UX Design</strong>
                    <p>Intuitive and accessible interfaces for users.</p>
                </li>
                <li>
                    <strong>Artificial Intelligence</strong>
                    <p>Data-driven systems powering smarter decisions.</p>
                </li>
            </ul>
        </section>


        <section class="values" aria-labelledby="values-title">
            <h2 id="values-title">Why Choose Us</h2>
            <p>
                At SmartTech Solutions, we believe that innovation thrives where
                creativity and collaboration meet. Our culture encourages continuous
                learning, open communication, and personal growth — empowering every
                member to contribute meaningfully to groundbreaking projects.
            </p>
        </section>


        <section class="cta" aria-labelledby="cta-title">
            <h2 id="cta-title">Join Our Team</h2>
            <p>
                Ready to take the next step in your career? Explore our current
                openings or submit your application today.
            </p>
            <p>
                <a href="jobs.php" class="btn primary">View Job Openings</a>
                <a href="apply.php" class="btn secondary">Apply Now</a>
            </p>
        </section>
    </main>


    <!-- FOOTER SECTION -->
    <?php include 'footer.inc'; ?>
</body>

</html>