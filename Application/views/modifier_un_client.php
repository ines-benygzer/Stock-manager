<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
unset($_SESSION['message'], $_SESSION['status']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Client</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('/Stock-manager/Snorkeling easybreath petite banner.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .form-container {
            text-align: center;
            margin: 150px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-container h1 { font-size: 24px; color: #4682B4; margin-bottom: 20px; }
        .form-container label { display: block; font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #4682B4; }
        .form-container input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-container input:focus {
            outline: none;
            border: 2px solid #00BFFF;
            transform: scale(1.02);
            box-shadow: 0 0 5px rgba(0, 191, 255, 0.5);
            transition: all 0.3s ease;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #87CEEB;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .form-container button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            background-color: #1E90FF;
        }
        .error-message { color: red; font-size: 14px; }
        .success-message { color: green; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Modifier un Client</h1>
        <form method="POST" action="/Stock-manager/public/clients/modifier" >
            <label>ID du Client :</label>
            <input type="number" name="id_client" placeholder="Entrez l'ID du client" required>

            <label>Nouveau numéro de téléphone :</label>
            <input type="text" name="telephone" placeholder="Entrez le nouveau numéro" required>

            <button type="submit" name="update">Modifier</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="<?php echo $status === 'error' ? 'error-message' : 'success-message'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
