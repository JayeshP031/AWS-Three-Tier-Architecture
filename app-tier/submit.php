<?php
declare(strict_types=1);

require_once __DIR__ . "/config.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function showMessage(string $title, string $message, int $status = 400): void
{
    http_response_code($status);
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, "UTF-8");

    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>{$safeTitle}</title><style>
        body{font-family:Arial,sans-serif;background:#101827;min-height:100vh;
        display:grid;place-items:center;margin:0;padding:20px}
        main{max-width:440px;background:#fff;padding:30px;border-radius:14px;
        text-align:center;box-shadow:0 15px 40px #0003}
        h1{color:#17243a;font-size:25px}p{color:#526078;line-height:1.6}
        a{display:inline-block;margin-top:12px;padding:12px 18px;
        background:#3867e8;color:white;text-decoration:none;border-radius:8px}
    </style></head><body><main><h1>{$safeTitle}</h1><p>{$safeMessage}</p>";
    echo "<a href='register.html'>Back to registration</a></main></body></html>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.html");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$mobile = trim($_POST["mobile"] ?? "");

if ($username === "" || strlen($username) > 50) {
    showMessage("Invalid username", "Enter a username of up to 50 characters.");
}
if (strlen($password) < 8) {
    showMessage("Invalid password", "Password must contain at least 8 characters.");
}
if (!preg_match("/^[0-9]{10}$/", $mobile)) {
    showMessage("Invalid mobile number", "Enter a valid 10-digit mobile number.");
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");

    $stmt = $conn->prepare(
        "INSERT INTO users (username, password, mobile) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $username, $passwordHash, $mobile);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    showMessage("Registration successful!", "Your details have been saved successfully.", 200);
} catch (mysqli_sql_exception $e) {
    // Do not expose database errors or credentials to the browser.
    if ((int)$e->getCode() === 1062) {
        showMessage("Username already exists", "Please choose another username.");
    }
    error_log("Registration database operation failed: " . $e->getMessage());
    showMessage("Registration failed", "Unable to save your details. Please try again.", 500);
}
?>
