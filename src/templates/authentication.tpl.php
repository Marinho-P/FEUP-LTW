<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    function drawLoginForm(Session $session)
    { ?>
        <form action="../actions/actionLogin.php" method="post" id="login">
            <label>
                <i class="fa-solid fa-user fa-xl"></i>
                <input type="text" name="username" placeholder="username" required>
            </label>
            <label>
                <i class="fa-solid fa-lock fa-xl"></i>
                <input type="password" name="password" placeholder="password" required>
            </label>
            <?php if ($session->getMessages()[0]['type'] == 'error') { ?>
                <p class="ErrorMessage"><?= $session->getMessages()[0]['text'] ?></p>
            <?php
            } ?>
            <button type="submit">LOGIN</button>
            <button type="button"><a href="register.php">REGISTER</a></button>
        </form>
    <?php }
    ?>


    <?php
    function drawSignupForm(Session $session)
    { ?>
        <form action="../actions/actionSignup.php" method="post" id="signup">
            <label>
                <i class="fa-solid fa-clipboard-user fa-xl"></i>
                <input type="text" name="name" placeholder="name" required>
            </label>
            <label>
                <i class="fa-solid fa-at fa-xl"></i>
                <input type="text" name="email" placeholder="email" required>
            </label>
            <label>
                <i class="fa-solid fa-user fa-xl"></i>
                <input type="text" name="username" placeholder="username" required>
            </label>
            <label>
                <i class="fa-solid fa-lock fa-xl"></i>
                <input type="password" name="password" placeholder="password" required>
            </label>
            <p>8 characters minimum with numbers, upper and lower letters and special characters</p>
            <label>
                <i class="fa-solid fa-check-double fa-xl"></i>
                <input type="password" name="confirm_password" placeholder="confirm password" required>
            </label>
            <button type="submit">REGISTER</button>
            <?php if ($session->getMessages()[0]['type'] == 'error') { ?>
                <p class="ErrorMessage"><?= $session->getMessages()[0]['text'] ?></p>
            <?php
            } ?>
        </form>
    <?php }
?>