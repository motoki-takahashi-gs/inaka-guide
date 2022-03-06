<header>
    <div class="wrap">
        <div class="title" id="title">
            <a href="./index.php">
                <img src="<?php echo $srcToLogo; ?>" alt="INAKA GUIDE - Experience living in rural areas">
            </a>
        </div>
        <!-- <form action="<?php echo $linkToHome; ?>" method="GET">
    <div class="search">
        <input type="search" name="q" placeholder="Keyword" value="<?php if (isset($query)) {
                                                                        echo specialChar($query);
                                                                    } ?>">
    </div>
</form> -->

        <nav class="nav-bar" id="nav-bar">
            <div><a href="<?php echo $linkToHome; ?>"><i class="fas fa-home"></i></a></div>
            <div><a href="<?php echo $linkToMap; ?>"><i class="fas fa-map-marker-alt"></i></a></div>
            <div><i class="fas fa-search start-search"></i></div>
            <div>
                <a href="<?php echo $linkToAccount; ?>">
                    <i class="fas fa-user-circle <?php echo $linkToAccountClass; ?>"></i>
                </a>
            </div>
            <div><i class="fas fa-bars"></i></div>
        </nav>

        <?php
        $loginOrLogout = '';

        if (isset($_SESSION['sid']) && isset($_SESSION['user_id'])) {
            $loginOrLogout = '<li><a href="./log-out.php">Log out</a></li>';
        } else {
            $loginOrLogout = '<li><a href="./log-in.php">Log in</a></li>';
            $loginOrLogout .= '<li><a href="./sign-up.php">Sign up</a></li>';
        }
        ?>

        <div class="menu-wrap">
            <input type="checkbox" class="toggler">
            <div class="hamburger">
                <div></div>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="<?php echo $linkToHome; ?>">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="<?php echo $linkToMap; ?>">Map</a></li>
                    <li><a href="#" class="start-search">Search</a></li>
                    <li>
                        <a href="<?php echo $linkToAccount; ?>" class="<?php echo $linkToAccountClass; ?>">Account</a>
                    </li>
                    <li><a href="#">Contact</a></li>
                    <?php echo $loginOrLogout; ?>
                </ul>
            </div>
        </div>
    </div>
</header>