<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Job openings and career opportunities at our IT company">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Job Openings</title>
</head>

<body class="jobs">

    <?php
    include 'header.inc';
    include 'settings.php';   // Đây là file tạo $conn
    ?>

    <main>
        <article>
            <h2>Explore Career Opportunities</h2>
            <p>
                Welcome to our Careers page. Here you will find current job openings available at our IT company.
                Each position description below outlines the key responsibilities, required qualifications, and
                desirable
                attributes for the role. Read carefully before submitting your application through the Apply page.
            </p>
        </article>

        <!-- STATIC ASIDE -->
        <aside id="jobs-aside" aria-labelledby="aside-title">
            <h2 id="aside-title">Why Work With Us?</h2>
            <p>
                We value creativity, collaboration, and continuous learning. Our employees are empowered to explore new
                technologies, share ideas, and grow their professional careers in an innovative environment.
            </p>

            <h3>Tips for Applicants</h3>
            <ul>
                <li>Read the job descriptions carefully before applying.</li>
                <li>Attach an updated CV and include relevant project links or portfolios.</li>
                <li>Use the Apply page to submit your application.</li>
                <li>Include the correct reference number for the position you are applying for.</li>
            </ul>
        </aside>

        <?php
        // Query job table
        $query = "SELECT * FROM jobs ORDER BY job_ref";

        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "<p>Database query failed: " . mysqli_error($conn) . "</p>";
            include 'footer.inc';
            exit();
        }

        // Display job listings dynamically
        if (mysqli_num_rows($result) > 0) {
            while ($job = mysqli_fetch_assoc($result)) {

                echo "<section class='job-block' id='{$job['job_ref']}'>";

                echo "<h2>{$job['title']} (Ref: {$job['job_ref']})</h2>";

                echo "<p><strong>Salary Range:</strong> {$job['salary']}</p>";

                echo "<p>{$job['description']}</p>";

                echo "<h3>Key Responsibilities</h3>";
                echo "<ul>";

                $requirements = explode(";", $job['requirements']);
                foreach ($requirements as $req) {
                    echo "<li>" . trim($req) . "</li>";
                }

                echo "</ul>";

                echo "<p><a href='apply.php?job_ref={$job['job_ref']}' class='btn primary'>Apply for this job</a></p>";

                echo "</section>";
            }
        } else {
            echo "<p>No job postings found.</p>";
        }

        mysqli_close($conn);
        ?>

        <section class="cta" aria-labelledby="cta-title">
            <h2 id="cta-title">Join Our Team</h2>
            <p>
                Ready to take the next step in your career? Explore our current openings or submit your application
                today.
            </p>
            <p>
                <a href='jobs.php' class='btn primary'>View Job Openings</a>
                <a href='apply.php' class='btn secondary'>Apply Now</a>
            </p>
        </section>
    </main>

    <?php include 'footer.inc'; ?>

</body>

</html>