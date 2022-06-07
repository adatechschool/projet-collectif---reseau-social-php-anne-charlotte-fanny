<?php
echo session_status();
if (session_status() == 2) {
  $userId = intval($_SESSION['connected_id']);
}
?>