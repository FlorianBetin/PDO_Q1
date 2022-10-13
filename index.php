
<?php
require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friendsArray = $statement->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST)){

// On récupère les informations saisies précédemment dans un formulaire
$firstname = trim($_POST['firstname']); 
$lastname = trim($_POST['lastname']);

// On prépare notre requête d'insertion
$query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
$statement = $pdo->prepare($query);

// On lie les valeurs saisies dans le formulaire à nos placeholders
$statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
$statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);

$statement->execute();
}

$errors = [];
if (!empty($_POST)) {
    $data = array_map('trim', $_POST);
    $firstname = htmlentities($data["firstname"]);
    $lastname = htmlentities($data["lastname"]);

    if (strlen($firstname) > 45) {
        $errors["firstname"] = "The name shouldn't exceed 45 characters";
    }

    if (strlen($lastname) > 45) {
        $errors["lastname"] = "The name shouldn't exceed 45 characters";
    }

    if(empty($errors)) {

    header('Location: index.php');
    echo 'Thank you !';
        }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" >
    <title>Document</title>
</head>

<header>


</header>

<body>
    
<ul>
<?php foreach($friendsArray as $friend) : ?>
<li><?= $friend['firstname'] . ' ' . $friend['lastname']; ?></li>



<?php endforeach; ?>
</ul>

<form method="POST">
    <div>  
  <label for="firstname">Prénom du Friend</label>
  <input type="text" name="firstname" id="firstname" placeholder="Prénom du Friend" maxlength="45" required>
  <span class="text-alert"><?= $errors["firstname"] ?? '' ?></span>
    </div>

    <div>  
  <label for="lastname">Nom du Friend</label>
  <input type="text" name="lastname" id="lastname" placeholder="Nom du Friend" maxlength="45" required>
  <span class="text-alert"><?= $errors["lastname"] ?? '' ?></span>
    </div>

    <button> Ajouter un Friend</button>
    <?php if(empty($errors)){
    echo '<p class= "thanks"> Thank you ! Your friend is added !</p>';
    }?>


  </form>

  </body>
</html>


