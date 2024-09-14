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
        $name = htmlspecialchars(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST['phone']));
        $position = htmlspecialchars(trim($_POST['position']));
        $bio = htmlspecialchars(trim($_POST['bio']));
        $password = htmlspecialchars(trim($_POST['password'])); // Plaintext password

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Invalid email format.');
        }

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO team_members (name, email, phone, position, bio, password_hash) VALUES (:name, :email, :phone, :position, :bio, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':password', $password); // Directly store plaintext password
        $stmt->execute();

        echo "<h1>Sign Up Successful</h1>";
        echo "<p>Thank you for signing up, " . htmlspecialchars($name) . "!</p>";
    } else {
        die('Invalid request method.');
    }

} catch (PDOException $e) {
    echo "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
}
?>
