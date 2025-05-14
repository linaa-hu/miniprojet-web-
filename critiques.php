<?php
$filename = "critiques.txt";
$dernieres = []; // ajouter cette ligne au début du bloc PHP dans index.php

// Suppression si demandée
if (isset($_GET['suppr'])) {
  $id_suppr = $_GET['suppr'];
  $lignes = file($filename);
  $nouveau = [];
  foreach ($lignes as $ligne) {
    list($id) = explode("|", $ligne, 2);
    if ($id != $id_suppr) $nouveau[] = $ligne;
  }
  file_put_contents($filename, implode("", $nouveau));
}

// Lecture et affichage
if (file_exists($filename)) {
  $critiques = file($filename);
  sort($critiques); // ordre croissant par ID

  echo '<div class="w3-bar w3-margin-bottom">';
for ($i = 1; $i <= 4; $i++) {
  echo "<button class='w3-button w3-pink w3-margin-right' style='border-radius: 15px;' onclick='filtrerNote($i)'>" . str_repeat("⭐", $i) . "</button> ";
}
echo "<button class='w3-button w3-pale-red' style='border-radius: 15px;' onclick='filtrerNote(0)'>💫 Toutes</button>";

  echo '</div><div class="w3-row-padding">';

  foreach ($critiques as $critique) {
  list($id, $titre, $type, $genre, $annee, $duree, $langue, $realisateur, $acteurs, $note, $texte, $posterURL) = explode('|', $critique);


    echo "
    <div class='w3-half note$note'>
      <div class='w3-card w3-padding w3-margin-bottom w3-pale-pink' style='border:2px solid #f48fb1; border-radius:20px; display:flex; gap:15px;'>
        <img src='" . htmlspecialchars($posterURL) . "' alt='Affiche de " . htmlspecialchars($titre) . "' style='width:100px; border-radius:10px; object-fit:cover;'>
        <div>
          <h3>" . htmlspecialchars($titre) . " ($annee)</h3>
          <p><b>Type :</b> $type | <b>Genre :</b> $genre</p>
          <p><b>Durée :</b> {$duree}min | <b>Langue :</b> $langue</p>
          <p><b>Réalisateur :</b> $realisateur</p>
          <p><b>Acteurs :</b> $acteurs</p>
          <p><b>Note :</b> " . str_repeat("⭐", intval($note)) . " ($note/4)</p>
          <p>" . nl2br(htmlspecialchars($texte)) . "</p>
          <a href='index.php?page=editcritique&id=$id' class='w3-button w3-blue' style='border-radius:20px;'>📝 Modifier</a>
          <a href='index.php?page=critiques&suppr=$id' class='w3-button w3-red' style='border-radius:20px;' onclick=\"return confirm('Supprimer cette critique ?')\">🗑️ Supprimer</a>
        </div>
      </div>
    </div>";
  }
  echo '</div>';
} else {
  echo "<p>Aucune critique pour l'instant.</p>";
}
?>

<script>
function filtrerNote(note) {
  const cards = document.querySelectorAll("[class*=note]");
  cards.forEach(card => {
    if (note === 0 || card.classList.contains("note" + note)) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}
</script>
