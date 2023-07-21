<?php

function encrypt($plaintext, $key)
{
    $iv = random_bytes(16); // Génère un vecteur d'initialisation aléatoire de 16 octets
    $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext);
}

function decrypt($ciphertext, $key)
{
    $ciphertext = base64_decode($ciphertext);
    $iv = substr($ciphertext, 0, 16);
    $ciphertext = substr($ciphertext, 16);
    return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['encrypt'])) {
        if (isset($_POST['key']) and !empty($_POST['key'])) {
            $key = $_POST['key'];
            $plaintext = $_POST['plaintext'];
            $encryptedText = encrypt($plaintext, $key);
        } else {
            $error = "Veuillez saisir une clé de chiffrement";
        }
    } elseif (isset($_POST['decrypt'])) {
        if (isset($_POST['key']) and !empty($_POST['key'])) {
            $key = $_POST['key'];
            $ciphertext = $_POST['ciphertext'];
            $decryptedText = decrypt($ciphertext, $key);
        } else {
            $error = "Veuillez saisir une clé de chiffrement";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Chiffrement et déchiffrement en PHP</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/x-icon" href="./Images/Symbole.ico">
    <script src="https://kit.fontawesome.com/f6fafc8c9a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="header">
        <div class="imgContainer">
            <img src="./Images/Symbole.png" alt="Logo" class="logo">
        </div>
        <div class="TitleContainer">
            <h1>Chiffrement et déchiffrement en PHP</h1>
        </div>
    </div>

    <div class="mainContent">
        <form method="post" action="">
            <label for="key">Clé de chiffrement :</label><br>
            <input type="text" name="key" id="key" value="<?php echo isset($key) ? $key : ''; ?>"><br>
            <br>
            <label for="plaintext">Texte à chiffrer :</label><br>
            <textarea name="plaintext" id="plaintext" cols="40" rows="5"><?php echo isset($plaintext) ? $plaintext : ''; ?></textarea><br>
            <input type="submit" name="encrypt" value="Chiffrer"><br>
            <br>
            <label for="ciphertext">Texte à déchiffrer :</label><br>
            <textarea name="ciphertext" id="ciphertext" cols="40" rows="5"><?php echo isset($ciphertext) ? $ciphertext : ''; ?></textarea><br>
            <input type="submit" name="decrypt" value="Déchiffrer"><br>
        </form>

        



        <?php if (isset($encryptedText)) : ?>
            <h2>Résultat du chiffrement :</h2>
            <div class="res"><p id="result"><?php echo $encryptedText; ?></p><button onclick="copyToClipboard()"><i class="fas fa-copy"></i></button></div>
            
        <?php endif; ?>

        <?php if (isset($decryptedText)) : ?>
            <h2>Résultat du déchiffrement :</h2>
            <p id="result"><?php echo $decryptedText; ?></p>
            <button onclick="copyToClipboard()"><i class="fas fa-copy"></i></button>
        <?php endif; ?>

        <?php if (isset($error)) : ?>
            <h2>Résultat du déchiffrement :</h2>
            <p><?php echo $decryptedText; ?></p>
        <?php endif; ?>
    </div>
</body>

<script>
    function copyToClipboard() {
        const paragraphText = document.querySelector('#result').textContent;
        const tempInput = document.createElement('textarea');
        tempInput.value = paragraphText;
        document.body.appendChild(tempInput);
        tempInput.select();

        document.execCommand('copy');
        document.body.removeChild(tempInput);

        alert('Le texte a été copié dans le presse-papiers.');
    }
</script>

</html>