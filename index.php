<?php
require_once 'includes/session.php';

if (isLoggedIn()) {
    header("Location: players/index.php");
} else {
    header("Location: auth/login.php");
}
exit;
