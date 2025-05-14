<h2 class="w3-center w3-xxlarge">🎬 Rédige votre petite critique !</h2>

<?php
//initiation des variables
$filename = "critiques.txt";
$id = $titre = $type = $genre = $annee = $note = $texte = $duree = $langue = $realisateur = $acteurs = $posterURL = "";
$mode_modification = false;

// Chargement des données existantes si modification
// Vérification de l'existence de l'ID dans l'URL
if (isset($_GET['id'])) {
  $mode_modification = true;
  $id = trim($_GET['id']);
  $lignes = file($filename);
  foreach ($lignes as $ligne) {
    list($lid, $ltitre, $ltype, $lgenre, $lannee, $lduree, $llangue, $lrealisateur, $lacteurs, $lnote, $ltexte, $lposterURL) = explode("|", trim($ligne));
    if ($lid === $id) {
      $titre = $ltitre;
      $type = $ltype;
      $genre = $lgenre;
      $annee = $lannee;
      $duree = $lduree;
      $langue = $llangue;
      $realisateur = $lrealisateur;
      $acteurs = $lacteurs;
      $note = $lnote;
      $texte = $ltexte;
      $posterURL = $lposterURL;
      break;
    }
  }
}

// Traitement du formulaire
//reception des données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //ajout d'un identifiant unique si mode ajout
  $id = trim($_POST["id"]);
  $mode = $_POST["mode"];
  if ($mode === "ajout") {
    $id = uniqid();
  }
  //trim: supprime les espaces en début et fin de chaîne
  $titre = trim($_POST["titre"]);
  $type = $_POST["type"];
  $genre = trim($_POST["genre"]);
  $annee = (int) $_POST["annee"];
  $duree = (int) $_POST["duree"];
  $langue = trim($_POST["langue"]);
  $realisateur = trim($_POST["realisateur"]);
  $acteurs = trim($_POST["acteurs"]);
  $note = (int) $_POST["note"];
  $texte = trim($_POST["texte"]);
  $posterURL = trim($_POST["posterURL"]);

  // Validation
  if (strlen($titre) < 3 || strlen($texte) < 10 || $annee < 1850 || $annee > 2025 || $note < 1 || $note > 4) {
    echo "<p>😿 Erreur : données invalides.</p>";
    //javascript:history.back() permet de revenir à la page précédente
    echo '<a href="javascript:history.back()" class="w3-button w3-red">⏪ Retour</a>';
    exit;
  }

  // Construction de la ligne
  $ligne = "$id|$titre|$type|$genre|$annee|$duree|$langue|$realisateur|$acteurs|$note|$texte|$posterURL\n";

  if ($mode === "modif") {
    //lire le fichier et remplacer la ligne correspondante
    $lignes = file($filename);
    $nouveau = [];
    //parcourir chaque ligne du fichier
    foreach ($lignes as $l) {
      list($lid) = explode("|", $l);

      if (trim($lid) === $id) $nouveau[] = $ligne;
      else $nouveau[] = $l;
    }
    file_put_contents($filename, implode("", $nouveau));
    echo "<p>🎨 Critique modifiée avec succès !</p>";
  } else {
    file_put_contents($filename, $ligne, FILE_APPEND);
    echo "<p>🌟 Critique ajoutée avec succès !</p>";
  }

  echo '<a href="index.php?page=critiques" class="w3-button w3-green">Retour à la liste</a>';
  exit;
}
?>

<form class="w3-card-4 w3-padding" method="POST" onsubmit="return validerFormulaire();">
  <input type="hidden" name="mode" value="<?= $mode_modification ? 'modif' : 'ajout'; ?>">

  <label>🆔 Identifiant :
    <input class="w3-input w3-light-grey" name="id" readonly value="<?= htmlspecialchars($id ?: uniqid()); ?>">
  </label>

  <label>🎀 Titre :
    <input class="w3-input" name="titre" value="<?= htmlspecialchars($titre) ?>">
  </label>

  <label>📼 Type :
    <input type="radio" name="type" value="film" <?= $type === "film" ? "checked" : "" ?>> Film
    <input type="radio" name="type" value="série" <?= $type === "série" ? "checked" : "" ?>> Série
  </label>

  <label>🍿 Genre :
    <input class="w3-input" name="genre" value="<?= htmlspecialchars($genre) ?>">
  </label>

  <label>📅 Année :
    <input class="w3-input" type="number" name="annee" value="<?= htmlspecialchars($annee) ?>">
  </label>

  <label>⏳ Durée (minutes) :
    <input class="w3-input" type="number" name="duree" value="<?= htmlspecialchars($duree) ?>">
  </label>

  <label>🗣️ Langue :
    <input class="w3-input" name="langue" value="<?= htmlspecialchars($langue) ?>">
  </label>

  <label>🎥 Réalisateur :
    <input class="w3-input" name="realisateur" value="<?= htmlspecialchars($realisateur) ?>">
  </label>

  <label>👩‍🎤 Acteurs :
    <input class="w3-input" name="acteurs" value="<?= htmlspecialchars($acteurs) ?>">
  </label>

  <label>💖 Note :
    <select class="w3-select" name="note">
      <?php
      for ($i = 1; $i <= 4; $i++) {
        $selected = ($note == $i) ? "selected" : "";
        echo "<option value='$i' $selected>" . str_repeat("⭐", $i) . " ($i/4)</option>";
      }
      ?>
    </select>
  </label>

  <label>📝 Commentaire :
    <textarea class="w3-input" name="texte"><?= htmlspecialchars($texte) ?></textarea>
  </label>

  <label>🖼️ URL de l'affiche :
    <input class="w3-input" name="posterURL" value="<?= htmlspecialchars($posterURL) ?>">
  </label>

  <br>
  <button type="submit" class="w3-button w3-pink">🍬 Enregistrer la critique</button>
</form>

<script>
function validerFormulaire() {
  const titre = document.forms[0].titre.value.trim();
  const texte = document.forms[0].texte.value.trim();
  const annee = parseInt(document.forms[0].annee.value);

  if (titre.length < 3 || texte.length < 10 || annee < 1850 || annee > 2025) {
    alert("Erreur : certains champs sont invalides.");
    return false;
  }
  return true;
}
</script>
