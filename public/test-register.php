<?php
// Test direct database insert
echo "<h2>Testing Database Insert</h2>";

// Database config
$host = 'localhost';
$db = 'movix_db';
$user = 'root';
$pass = '';

try {
    // Connect using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected successfully!<br><br>";
    
    // Test insert
    $email = 'test' . time() . '@gmail.com';
    $password = password_hash('123456', PASSWORD_DEFAULT);
    $role = 'User';
    
    echo "Attempting to insert:<br>";
    echo "Email: $email<br>";
    echo "Password (hashed): " . substr($password, 0, 30) . "...<br>";
    echo "Role: $role<br><br>";
    
    $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    
    $result = $stmt->execute([
        'email' => $email,
        'password' => $password,
        'role' => $role
    ]);
    
    if ($result) {
        $lastId = $pdo->lastInsertId();
        echo "✅ <strong>INSERT SUCCESSFUL!</strong> ID: $lastId<br><br>";
    } else {
        echo "❌ Insert failed!<br>";
    }
    
    // Show all users
    echo "<hr><h3>All Users in Database:</h3>";
    $stmt = $pdo->query("SELECT id, email, role, created_at FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Email</th><th>Role</th><th>Created At</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }
    
} catch (PDOException $e) {
    echo "❌ <strong>ERROR:</strong> " . $e->getMessage();
}
?>

<hr>
<h3>Test Form Register</h3>
<form method="post" action="test-register-process.php">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>
    
    <button type="submit">Test Register</button>
</form>