<?php
include "db_connect.php";

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator and Saver</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .card {
            background: #fff;
            border: 1px solid #d5dbe1;
            padding: 15px;
            border-radius: 4px;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #bfc7d1;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .check-group {
            margin-top: 10px;
        }

        .check-group label {
            display: inline-block;
            margin-right: 10px;
            margin-top: 0;
        }

        button,
        .btn-link {
            margin-top: 12px;
            background: #2f6fb2;
            color: white;
            border: 1px solid #2a639f;
            padding: 10px 14px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-link.delete {
            background: #7a7f87;
            border-color: #676b72;
            padding: 6px 10px;
            margin-top: 0;
        }

        .strength {
            margin-top: 10px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #cfd6dd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background: #e9eef3;
        }

        .search-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        .search-row input {
            flex: 1;
        }

        @media (max-width: 850px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <h1>Password Generator and Saver</h1>

    <div class="container">
        <div class="card">
            <h3>1) Generate Password</h3>

            <label>Password Length</label>
            <input type="number" id="p_length" value="10" min="4" max="50">

            <div class="check-group">
                <label><input type="checkbox" id="upper" checked> Uppercase</label>
                <label><input type="checkbox" id="lower" checked> Lowercase</label>
                <label><input type="checkbox" id="number" checked> Numbers</label>
                <label><input type="checkbox" id="symbol"> Symbols</label>
            </div>

            <button type="button" onclick="generatePassword()">Generate</button>

            <label>Generated Password</label>
            <input type="text" id="generated_password" readonly>

            <div class="strength" id="strength_text">Strength: -</div>
        </div>

        <div class="card">
            <h3>2) Save Password</h3>
            <form action="save_password.php" method="POST">
                <label>Account Name</label>
                <input type="text" name="account_name" placeholder="Example: Facebook" required>

                <label>Username / Email</label>
                <input type="text" name="username" placeholder="Example: mymail@gmail.com" required>

                <label>Password</label>
                <input type="text" name="password_val" id="save_password_input" required>

                <button type="submit" name="submit">Save</button>
            </form>
        </div>
    </div>

    <div class="card" style="margin-top: 20px;">
        <h3>3) Saved Passwords</h3>

        <form action="index.php" method="GET" class="search-row">
            <input type="text" name="search" placeholder="Search by account name" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
            <a href="index.php" class="btn-link">Clear</a>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Account Name</th>
                <th>Username/Email</th>
                <th>Password</th>
                <th>Action</th>
            </tr>

            <?php
            // Get data with optional search
            if ($search != "") {
                $sql = "SELECT * FROM vault WHERE account_name LIKE '%$search%' ORDER BY id DESC";
            } else {
                $sql = "SELECT * FROM vault ORDER BY id DESC";
            }

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['account_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['password_val']) . "</td>";
                    echo "<td><a class='btn-link delete' href='delete_password.php?id=" . $row['id'] . "' onclick='return confirm(\"Delete this entry?\")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No saved passwords found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        // This generates password based on selected options
        function generatePassword() {
            var p_length = parseInt(document.getElementById('p_length').value);
            var useUpper = document.getElementById('upper').checked;
            var useLower = document.getElementById('lower').checked;
            var useNumber = document.getElementById('number').checked;
            var useSymbol = document.getElementById('symbol').checked;

            var upperChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var lowerChars = 'abcdefghijklmnopqrstuvwxyz';
            var numberChars = '0123456789';
            var symbolChars = '!@#$%^&*()_+{}[]';

            var allChars = '';

            if (useUpper) {
                allChars += upperChars;
            }
            if (useLower) {
                allChars += lowerChars;
            }
            if (useNumber) {
                allChars += numberChars;
            }
            if (useSymbol) {
                allChars += symbolChars;
            }

            if (allChars === '') {
                alert('Please select at least one character type.');
                return;
            }

            var newPassword = '';
            for (var i = 0; i < p_length; i++) {
                var randomIndex = Math.floor(Math.random() * allChars.length);
                newPassword += allChars[randomIndex];
            }

            document.getElementById('generated_password').value = newPassword;
            document.getElementById('save_password_input').value = newPassword;

            // Simple strength rules from requirement
            var strengthText = 'Strength: Weak';
            var strengthColor = 'red';

            if (p_length >= 12 && useSymbol) {
                strengthText = 'Strength: Strong';
                strengthColor = 'green';
            } else if (p_length >= 8 && useNumber) {
                strengthText = 'Strength: Medium';
                strengthColor = 'orange';
            }

            var strengthEl = document.getElementById('strength_text');
            strengthEl.textContent = strengthText;
            strengthEl.style.color = strengthColor;
        }
    </script>
</body>
</html>
