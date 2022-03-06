<?php

include("../includes/functions.php");

checkSid();

$pageName = 'Create account';

?>

<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body class="create-account">

    <?php include('../includes/header.php'); ?>

    <main>
        <section>
            <h1>Create an account</h1>
            <form action="create-account-process.php" method="POST" enctype="multipart/form-data">
                <div>
                    <input type="text" name="first_name" placeholder="First name" required>
                </div>
                <div>
                    <input type="text" name="last_name" placeholder="Last name" required>
                </div>
                <div>
                    <input type="text" name="company_name" placeholder="Company name" required>
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email address" required>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div>
                    <textarea name="about" placeholder="About" required></textarea>
                </div>
                <div class="image">
                    <div>
                        <input type="file" name="image" id="real_button" accept="image/*" hidden="hidden" required>
                        <button type="button" id="custom_button">Upload your image</button>
                    </div>
                    <div class="image_thumbnail">
                        <img src="" id="image_thumbnail">
                    </div>
                </div>
                <div><button type="submit">Create account</button></div>
            </form>
        </section>
    </main>

    <script src="../js/create-account.js"></script>

</body>

</html>