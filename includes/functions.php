<?php
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

function formatDate($date) {
    return date("F j, Y", strtotime($date));
}

function getStatusBadge($status) {
    $colors = [
        'active' => 'success',
        'inactive' => 'secondary',
        'present' => 'success',
        'absent' => 'danger',
        'leave' => 'warning',
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'open' => 'primary',
        'in_progress' => 'info',
        'done' => 'success'
    ];
    $color = $colors[$status] ?? 'secondary';
    return "<span class='badge bg-$color'>" . ucfirst(str_replace('_', ' ', $status)) . "</span>";
}
?>
