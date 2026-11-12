<?php
function hms_escape($value)
{
    if (!is_scalar($value) && $value !== null) {
        $value = (string) $value;
    }

    if ($value === null) {
        return '';
    }

    $value = trim((string) $value);
    $conn = $GLOBALS['con'] ?? null;

    if ($conn && function_exists('mysqli_real_escape_string')) {
        return mysqli_real_escape_string($conn, $value);
    }

    return addslashes($value);
}

function hms_hash_password($password)
{
    return password_hash((string) $password, PASSWORD_DEFAULT);
}

function hms_verify_password($password, $storedPassword)
{
    $password = (string) $password;
    $storedPassword = (string) $storedPassword;

    if ($password === '' && $storedPassword === '') {
        return true;
    }

    if (password_verify($password, $storedPassword)) {
        return true;
    }

    return hash_equals($password, $storedPassword);
}

function hms_mask_password($password)
{
    $password = (string) $password;
    if ($password === '') {
        return '';
    }

    $length = strlen($password);
    if ($length <= 4) {
        return str_repeat('*', max(8, $length));
    }

    return str_repeat('*', $length);
}

function hms_email_exists($email, $table = 'patreg')
{
    $conn = $GLOBALS['con'] ?? null;
    if (!$conn || trim((string) $email) === '') {
        return false;
    }

    $sanitizedEmail = hms_escape($email);
    $query = "SELECT 1 FROM `{$table}` WHERE email = '{$sanitizedEmail}' LIMIT 1";
    $result = mysqli_query($conn, $query);

    return $result && mysqli_num_rows($result) > 0;
}
