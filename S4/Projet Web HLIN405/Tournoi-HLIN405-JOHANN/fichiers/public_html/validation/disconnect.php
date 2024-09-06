<?php 

require_once(__DIR__ . '/../site-header.php');

if (!isset($_GET['page'])) {
    ph_error_redirect(403);
}

ph_set_redirect();

ph_disconnect_user();

header('Location: ' . ph_get_redirect());
ph_remove_redirect();
if (http_response_code() !== 200) {
    header('Location: ' . ph_get_route_link(ROOT));
}
exit;