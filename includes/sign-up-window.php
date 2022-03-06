<?php

if (strpos($_SERVER['REQUEST_URI'], 'sign-up.php')) {
    $linkToLogIn = 'log-in.php';
} else {
    $linkToLogIn = '#log-in';
}

?>

<div class="modal" id="sign-up-modal">
    <div class="sign-up">
        <h1>Sign up</h1>
        <form action="sign-up-process.php" method="POST">
            <div>
                <input type="text" name="first_name" placeholder="First name" required>
            </div>
            <div>
                <input type="text" name="last_name" placeholder="Last name" required>
            </div>
            <div>
                <input type="email" name="email" placeholder="Email address" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div><input type="hidden" name="redirect-to"></div>
            <div><button type="submit">Sign up</button></div>
        </form>
        <div class="navigate-to-log-in">
            <p>Do you have an account?</p>
            <p><a href="<?php echo $linkToLogIn; ?>" id="link-to-log-in">Log in</a></p>
        </div>
        <span class="close">&times;</span>
    </div>
</div>