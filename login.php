<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password (empty)
$dbname = "team_db"; // The database you created

// Create a new PDO instance
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize form inputs
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST['password'])); // Plaintext password

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Invalid email format.');
        }

        // Prepare and execute the SQL statement to fetch member
        $sql = "SELECT * FROM team_members WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the result
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a matching record was found and validate password
        if ($member && $password === $member['password']) {
            echo "<h1>Login Successful</h1>";
            echo "<h2>Member Details</h2>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($member['name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($member['email']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($member['phone']) . "</p>";
            echo "<p><strong>Position:</strong> " . htmlspecialchars($member['position']) . "</p>";
            echo "<p><strong>Bio:</strong> " . htmlspecialchars($member['bio']) . "</p>";
        } else {
            echo "<h1>Login Failed</h1>";
            echo "<p>Invalid email or password. Please try again.</p>";
        }
    } else {
        die('Invalid request method.');
    }

} catch (PDOException $e) {
    echo "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
}
?>
