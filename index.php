<?php 
$filename = "critiques.txt";
$critiques = []; 
$dernieres = [];

//si le fichier critiques.txt existe, on le lit
if (file_exists($filename)) { 
    //file: lit le fichier et retourne un tableau contenant chaque ligne
    $lignes = file($filename); 
    foreach ($lignes as $ligne) { 
        $champs = explode("|", trim($ligne));

        if (count($champs) >= 12) {
            list($id, $titre, $type, $genre, $annee, $duree, $langue, $realisateur, $acteurs, $note, $texte, $posterURL) = $champs;
            $critiques[] = [
                "id" => $id,
                "titre" => $titre,
                "annee" => (int)$annee,
                "poster" => $posterURL,
                "note" => $note
            ];
        }
    }
    
    //usort: trier le tableau par année décroissante
    // <=> : opérateur de comparaison combiné
    usort($critiques, function($a, $b) {
        return $b['annee'] <=> $a['annee'];
    });
    //array_slice: retourne une partie du tableau
    //ici, on prend les 4 premières critiques
    $dernieres = array_slice($critiques, 0, 4);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Critiques Films/Séries</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="w3-padding w3-center header-mignon">
  <img src="logo.png" alt="Logo" class="logo-mignonne">
  <h1 class="titre-mignon">🌸 Critiques de Films et Séries 🌸</h1>
</header>

<div class="w3-row">
  <!-- Menu latéral -->
  <div class="w3-col l2 m3 w3-bar-block w3-light-grey">
    <a href="index.php?page=presentation" class="w3-bar-item w3-button">🎀 Présentation</a>
    <a href="index.php?page=critiques" class="w3-bar-item w3-button">🍭 Voir les critiques</a>
    <a href="index.php?page=editcritique" class="w3-bar-item w3-button">🧁 Ajouter une critique</a>
  </div>

  <!-- Contenu principal -->
  <div class="w3-col l10 m9 w3-padding">
    <?php
      if (isset($_GET['page'])) {
        
        if ($_GET['page'] == 'critiques') {
          include("critiques.php");
        } elseif ($_GET['page'] == 'editcritique') {
          include("editcritique.php");
        } elseif ($_GET['page'] == 'presentation') {
          echo '
          <div class="w3-container">
            <h2 class="w3-xxlarge w3-center">🍒 Qui suis-je ?</h2>
            <p class="w3-large w3-padding">
              Bienvenue sur mon petit site de critique de film et serie  mignon ! Ici, tout les personnes peuvent partager leurs critiques sincères et sucrées 
              sur ses films et séries préférés. Le site est fait  avec du PHP, du CSS et java mignon et beaucoup de passion 🎬✨.
            </p>
            <p class="w3-large">N’hésitez pas à parcourir les critiques ou ajouter la vôtre. Bon visionnage ! 💖</p>
          </div>';
        } else {
          include("init.php");
        }
      } else {
        include("init.php");
      }

      // Dernières critiques
      if (!empty($dernieres)) {
        echo '
        <div class="w3-container">
          <h2 class="w3-xxlarge w3-center">🎞️ Dernières critiques</h2>
          <div class="w3-row-padding w3-margin-top">';
        foreach ($dernieres as $c) {
          echo '
            <div class="w3-quarter w3-center">
              <div class="w3-card-4 w3-margin w3-white">
                <img src="' . htmlspecialchars($c['poster']) . '" alt="Affiche" style="width:100%; height:300px; object-fit:cover">
                <div class="w3-container">
                  <h4>' . htmlspecialchars($c['titre']) . ' (' . $c['annee'] . ')</h4>
                  <p>' . str_repeat("⭐", (int)$c['note']) . ' (' . $c['note'] . '/4)</p>
                </div><a href="index.php?page=critiques&id=' . $c['id'] . '" class="w3-button w3-blue w3-small">Voir fiche</a>

              </div>
            </div>';
        }
        echo '</div></div>';
      }
    ?>
  </div>
</div>

<footer class="w3-container w3-light-pink w3-padding-16">
  <p>&copy; 2025 | Lina Ben Abdelkader | <a href="#">Facebook</a> | <a href="#">Twitter</a> | <a href="#">Instagram</a></p>
</footer>

<img src="https://media.giphy.com/media/l0MYt5jPR6QX5pnqM/giphy.gif" 
     alt="Licorne magique"
     style="position: fixed; bottom: 10px; right: 10px; width: 80px; z-index: 1000;">
     

</body>
</html>
