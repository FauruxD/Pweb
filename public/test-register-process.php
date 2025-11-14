<?php
// Process test register
echo "<h2>Processing Registration</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    echo "Received data:<br>";
    echo "Email: $email<br>";
    echo "Password: " . str_repeat('*', strlen($password)) . "<br>";
    echo "Confirm: " . str_repeat('*', strlen($confirm)) . "<br><br>";
    
    // Validation
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match";
    }
    
    if (!empty($errors)) {
        echo "<strong>❌ Validation Errors:</strong><br>";
        foreach ($errors as $error) {
            echo "- $error<br>";
        }
        echo "<br><a href='test-register.php'>Back</a>";
        exit;
    }
    
    // Database config
    $host = 'localhost';
    $db = 'movix_db';
    $user = 'root';
    $pass = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ Database connected<br>";
        
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->fetch()) {
            echo "❌ <strong>Email already exists!</strong><br>";
            echo "<a href='test-register.php'>Back</a>";
            exit;
        }
        
        echo "✅ Email is unique<br>";
        
        // Insert user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute([
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'User'
        ]);
        
        if ($result) {
            $lastId = $pdo->lastInsertId();
            echo "✅ <strong style='color: green;'>REGISTRATION SUCCESSFUL!</strong><br>";
            echo "User ID: $lastId<br>";
            echo "Email: $email<br><br>";
            echo "<a href='test-register.php'>View All Users</a>";
        } else {
            echo "❌ Insert failed!<br>";
        }
        
    } catch (PDOException $e) {
        echo "❌ <strong>Database Error:</strong> " . $e->getMessage() . "<br>";
        echo "<br><a href='test-register.php'>Back</a>";
    }
    
} else {
    echo "Invalid request method<br>";
    echo "<a href='test-register.php'>Back</a>";
}
?>