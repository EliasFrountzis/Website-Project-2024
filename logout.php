<?php
session_start();
session_destroy();
// ανακατεύθυνση στο login
header('Location: signin.html');
?>