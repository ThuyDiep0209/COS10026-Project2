<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="About Group 02 - COS10026 Web Technology Project">
    <meta name="keywords" content="SmartTech, Group 02, COS10026, Web Project, About Us">
    <title>About Us | SmartTech Solutions</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body class="about-page">
    <?php include 'header.inc'; ?>
    <main>
        <section>
            <h2>Group Information</h2>
            <ul>
                <li><strong>Group Name:</strong> Group 02</li>
                <li><strong>Class Time and Day:</strong> Friday, 2:00 PM - 5:00 PM</li>
                <li><strong>Tutor:</strong> Ms. Nguyen Thuy Linh</li>
                <li>
                    <strong>Members (Name and ID):</strong>
                    <ul>
                        <li>Vu Hai Anh (104848796)</li>
                        <li>Vo Quynh Thu (105978005)</li>
                        <li>Le Thi Thuy Diep (104188447)</li>
                    </ul>
                </li>
            </ul>
        </section>

        <section>
            <h2>Members' Contributions</h2>
            <dl>
                <dt>Le Thi Thuy Diep</dt>
                <dd>Complete fontend: header.inc; nav.inc; footer.inc; index.php; job.php; about.php; setting.php,
                    create table SQL
                </dd>
                <dt>Vo Quynh Thu</dt>
                <dd>Complete backend: appy.php; manage.php </dd>


            </dl>
        </section>

        <section>
            <h2>Group Photo</h2>
            <figure>
                <img src="images/team_photo.png" alt="Group 02 team photo" width="350">
                <figcaption>Our Group 02 - COS10026 Web Technology Project Team</figcaption>
            </figure>
            <p>
                We are Group 02 from the Friday 2-5 PM class. Our team worked collaboratively
                on this SmartTech Solutions project to design and build a professional static website using PHP language
                programming.
            </p>
        </section>

        <section>
            <h2>Members' Interests</h2>
            <table>
                <caption>Group Members' Interests and Hobbies</caption>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Interests</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Le Thi Thuy Diep</td>
                        <td>Reading, Music</td>
                    </tr>
                    <tr>
                        <td>Vo Quynh Thu</td>
                        <td>Music, Reading, Fashion, Travelling, Cooking</td>
                    </tr>

                </tbody>
            </table>
        </section>

        <section>
            <h2>Our Personalities</h2>

            <article>
                <figure>
                    <img src="images/tho.png" alt="Bunny icon representing Diep" width="120">
                    <h3>Le Thi Thuy Diep</h3>
                </figure>
                <ul>
                    <li>A caring person who supports team members</li>
                    <li>Team Member</li>
                </ul>
            </article>

            <article>
                <figure>
                    <img src="images/rua.png" alt="Turtle icon representing Quynh Thu" width="120">
                    <h3>Vo Quynh Thu</h3>
                </figure>
                <ul>
                    <li>An introverted but confident person</li>
                    <li>Team Member</li>
                </ul>
            </article>


        </section>
    </main>

    <?php include 'footer.inc'; ?>
</body>

</html>