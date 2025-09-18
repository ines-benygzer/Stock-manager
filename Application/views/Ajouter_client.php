<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    <title>Ajouter un Client</title>
    <style>
        /* KEEP ALL ORIGINAL CSS FROM YOUR VIEW */
        body {
            margin: 0;
            padding: 0;
            background-image: url('canoe-kayak-gonflable-de-randonnee-23-places.jpg.avif');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: #333;
            height: 100vh;
        }
        .classform {
            text-align: center;
            margin: 5% auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #87CEEB;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
        }
        input[type="text"]:focus, input[type="email"]:focus {
            border-color: #00BFFF;
            box-shadow: 0 0 5px #00BFFF;
        }
        .classgenre {
            display: flex;
            align-items: center;
            justify-content: space-around;
            margin: 20px 0;
        }
        .classgenre label { font-size: 16px; font-weight: bold; }
        .classgenre input[type="radio"] { margin-right: 5px; }
        .classbutton button {
            display: block;
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
            transition: background-color 0.3s ease;
        }
        .classbutton button:hover { background-color: #00BFFF; }
    </style>
</head>
<body>
    <div class="classform">
        <h1>Bienvenue!</h1>
        <form method="POST" action="/inesbenygzer/public/clients/ajouter ">
            <div class="classemail">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="classphone">
                <label>Téléphone:</label>
                <input type="text" name="telephone" required>
            </div>
            <div class="classgenre">
                <label><input type="radio" name="genre" value="homme" required> Homme</label>
                <label><input type="radio" name="genre" value="femme" required> Femme</label>
            </div>
            <div class="classnometprenom">
                <label>Nom et Prénom:</label>
                <input type="text" name="nom" required>
            </div>
            <div class="classadresse">
                <label>Adresse:</label>
                <input type="text" name="adresse" required>
            </div>
            <div class="classbutton">
                <button type="submit" name="create" >Ajouter le Client</button>
            </div>
        </form>
        <?php if (!empty($message)): ?>
            <p class="<?php echo $status === 'error' ? 'error-message' : 'success-message'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </div>
    <script>
        const inputs = document.querySelectorAll("input");
        inputs.forEach(input => {
            input.addEventListener("focus", () => {
                input.style.transform = "scale(1.05)";
                input.style.borderColor = "#00BFFF";
                input.style.transition = "all 0.3s ease";
            });
            input.addEventListener("blur", () => {
                input.style.transform = "scale(1)";
                input.style.borderColor = "#ccc";
            });
        });
        const button = document.querySelector("button");
        button.addEventListener("mouseenter", () => { button.style.transform = "scale(1.1)"; });
        button.addEventListener("mouseleave", () => { button.style.transform = "scale(1)"; });
    </script>
</body>
</html>
