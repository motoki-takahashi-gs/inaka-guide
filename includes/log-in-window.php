<?php

if (strpos($_SERVER['REQUEST_URI'], 'log-in.php')) {
    $linkToSignUp = 'sign-up.php';
} else {
    $linkToSignUp = '#sign-up';
}

?>

<div class="modal" id="log-in-modal">
    <div class="log-in">
        <h1>Log in</h1>
        <form action="log-in-process.php" method="POST">
            <div><input type="email" name="email" placeholder="Email address" required></div>
            <div><input type="password" name="password" placeholder="Password" required></div>
            <div><input type="hidden" name="redirect-to"></div>
            <div><button type="submit">Log in</button></div>
        </form>
        <div class="navigate-to-sign-up">
            <p>Don't you have an account?</p>
            <p><a href="<?php echo $linkToSignUp; ?>" id="link-to-sign-up">Sign up</a></p>
        </div>
        <span class="close">&times;</span>
    </div>
</div>