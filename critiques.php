<?php
$lines = file("data.txt", FILE_IGNORE_NEW_LINES);
sort($lines); // tri par ID

echo '<div class="w3-bar w3-margin-bottom">
        <button onclick="filterByNote(0)" class="w3-button">Toutes</button>';
for ($i = 1; $i <= 4; $i++) {
    echo "<button onclick=\"filterByNote($i)\" class='w3-button'>Note $i</button>";
}
echo '</div>';

echo '<div class="w3-row-padding">';
foreach ($lines as $line) {
    list($id, $titre, $type, $annee, $note, $critique) = explode('|', $line);
    echo "<div class='w3-col l6 w3-margin-bottom card note$note'>
            <div class='w3-card w3-padding'>
                <h3>$titre ($annee)</h3>
                <p><b>Type :</b> $type</p>
                <p><b>Note :</b> " . str_repeat("⭐", $note) . "</p>
                <p>$critique</p>
                <a href='index.php?page=editcritique&id=$id' class='w3-button w3-blue'>Modifier</a>
                <a href='index.php?page=critiques&delete=$id' class='w3-button w3-red'>Supprimer</a>
            </div>
        </div>";
}

// Supprimer une critique
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $newData = array_filter($lines, function($line) use ($idToDelete) {
        return explode('|', $line)[0] != $idToDelete;
    });
    file_put_contents("data.txt", implode("\n", $newData));
    header("Location: index.php?page=critiques");
    exit;
}
echo '</div>';
?>
<script src="script.js"></script>
