<?php
$id = $_GET['id'] ?? null;
$data = file_exists("data.txt") ? file("data.txt", FILE_IGNORE_NEW_LINES) : [];
$critiqueData = ["", "", "", "", "", ""];

if ($id !== null) {
    foreach ($data as $line) {
        $parts = explode('|', $line);
        if ($parts[0] == $id) {
            $critiqueData = $parts;
            break;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $type = $_POST["type"];
    $annee = $_POST["annee"];
    $note = $_POST["note"];
    $text = $_POST["critique"];
    $isModif = isset($_POST["id"]);
    $id = $isModif ? $_POST["id"] : time();

    $line = "$id|$titre|$type|$annee|$note|$text";

    if ($isModif) {
        foreach ($data as $k => $v) {
            if (explode('|', $v)[0] == $id) {
                $data[$k] = $line;
                break;
            }
        }
    } else {
        $data[] = $line;
    }

    file_put_contents("data.txt", implode("\n", $data));
    echo "<p>Critique enregistrée. <a href='index.php?page=critiques'>Retour</a></p>";
    exit;
}
?>

<form class="w3-card w3-padding" method="POST" onsubmit="return validateForm()">
    <?php if ($id !== null): ?>
        <p><b>ID : </b> <input type="text" name="id" value="<?= $critiqueData[0] ?>" readonly></p>
    <?php endif; ?>
    <p><input class="w3-input" name="titre" placeholder="Titre" value="<?= $critiqueData[1] ?>"></p>
    <p>
        <select class="w3-select" name="type">
            <option value="film" <?= $critiqueData[2]=="film"?"selected":"" ?>>Film</option>
            <option value="série" <?= $critiqueData[2]=="série"?"selected":"" ?>>Série</option>
        </select>
    </p>
    <p><input class="w3-input" name="annee" placeholder="Année" value="<?= $critiqueData[3] ?>"></p>
    <p><input class="w3-input" name="note" type="number" min="1" max="4" placeholder="Note" value="<?= $critiqueData[4] ?>"></p>
    <p><textarea class="w3-input" name="critique" placeholder="Votre critique"><?= $critiqueData[5] ?></textarea></p>
    <p><button type="submit" class="w3-button w3-green">Enregistrer</button></p>
</form>

<script>
function validateForm() {
    const inputs = document.querySelectorAll("input, textarea, select");
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value === "") {
            alert("Veuillez remplir tous les champs.");
            return false;
        }
    }
    return true;
}
</script>
