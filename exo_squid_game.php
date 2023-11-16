<?php


class Game // class permettant de créer une partie
{
    private $hero; // On crée une variable $hero qui represente le héros
    private $ennemis; // On crée une variable $ennemis qui represente les ennemis
    private $level; // On crée une variable $level qui represente le niveau de difficulté

    public function __construct()
    { // Constructeur permettant de créer une nouvelle partie
        $this->initializeHero();
        $this->initializeEnnemis();
        $this->chooseDifficulty();
        $this->chooseHero();
    }

    private function initializeHero()
    {
        $this->hero = [ // Créer un array contenant les 3 héros
            new Hero('Seong Gi-hun', 15, 1, 2, 'Gagné !'), // Ici il s'agit de la création d'un nouvel objet Hero pour chaque héros
            new Hero('Kang Sae-byeok', 25, 2, 1, 'Houra !'),
            new Hero('Cho Sang-woo', 35, 3, 0, 'Je t ai eu !')
        ];
    }

    private function initializeEnnemis()
    {
        $this->ennemis = [];
        for ($i = 1; $i <= 20; $i++) { // $i = 1; $i <= 20; $i++ est une boucle for qui permet de créer 20 ennemis
            $this->ennemis[] = new Ennemi('Opponent ' . $i, Utils::generateRandomNumber(1, 20), Utils::generateRandomNumber(18, 60)); // Ici il s'agit de la création d'un nouvel objet Ennemi pour chaque ennemi avec les classes Ennemis et Utils
        }
    }

    private function manageCombat($currentEnemy)
    { // Cette fonction permet gérer le combat entre le héros et l'ennemi
        foreach ($this->hero as $hero) { // foreach permet de parcourir le tableau $hero et de récupérer chaque valeur dans la variable $hero
            echo "L'héros actuel est : {$hero->getName()}, Billes: {$hero->getBilles()}\n"; // Ici on affiche le nom et le nombre de billes du héros
            echo "L'héros actuel est : {$currentEnemy->getName()}, Billes: {$currentEnemy->getBilles()}, Age: {$currentEnemy->getAge()}\n"; // Là on affiche le nom, le nombre de billes et l'âge de l'ennemi

            $isEven = $currentEnemy->checkBilles(); // On vérifie si le nombre de billes de l'ennemi est pair ou impair
            $hero->makeChoice($isEven);

            if ($hero->checkChoice($isEven)) { // checkchoice($isEven) permet de vérifier si le choix du héros est pair ou impair
                $this->winEncounter($hero, $currentEnemy); //
            } else {
                $this->loseEncounter($hero, $currentEnemy);
            }

            echo "----------\n";
        }
    }

    public function jouerPartie()
    {
        $tour = $this->jouerTour();  // Assurez-vous que $tour est défini correctement
        $touractuel = 1;

        while ($tour > 0 && count($this->ennemis) > 0) {
            echo "Round $touractuel\n";
            $currentEnemy = $this->chooseEnemy();
            $this->manageCombat($currentEnemy);
            $this->removeEnemy($currentEnemy);
            $touractuel++;
            $tour--;
        }

        $this->resultatPartie();
    }

    private function removeEnemy($enemyToRemove)
    {
        if (!empty($this->ennemis)) {
            foreach ($this->ennemis as $key => $enemy) {
                if ($enemy === $enemyToRemove) {
                    unset($this->ennemis[$key]);
                    break;
                }
            }
            $this->ennemis = array_values($this->ennemis); // Réorganiser les indices du tableau
        }
    }

    private function winEncounter($hero, $ennemis)
    { // Cette fonction permet de gérer la victoire du héros
        $bonus = $hero->getVictoire(); // On récupère le bonus du héros
        $hero->setBilles($hero->getBilles() + $ennemis->getBilles() + $bonus); // On ajoute le nombre de billes de l'ennemi et le bonus du héros au nombre de billes du héros
        echo "{$hero->getName()} a gagné! {$ennemis->getName()} est éliminé.\n";
    }

    private function loseEncounter($hero, $ennemis)
    { // Cette fonction permet de gérer la défaite du héros
        $malus = $hero->getPerdu(); // On récupère le malus du héros
        $hero->setBilles($hero->getBilles() - $ennemis->getBilles() - $malus); // On soustrait le nombre de billes de l'ennemi et le malus du héros au nombre de billes du héros
        echo "{$hero->getName()} a perdu ! {$ennemis->getName()} a {$ennemis->getBilles()} billes.\n";

        if ($hero->getBilles() <= 0) { // Si le nombre de billes du héros est inférieur ou égal à 0, alors le héros a perdu
            echo "{$hero->getName()} a perdu toutes ses billes. Fin de partie.\n";
            exit;
        }
    }

    public function rejouerPartie()
    { // Cette fonction permet de rejouer une partie
        $this->initializeHero();
        $this->initializeEnnemis();
        $this->jouerPartie();
    }

    private function jouerTour()
    { // Cette fonction permet de jouer un tour avec le choix de 3 niveaux de difficulté
        switch ($this->level) {
            case 'Facile':
                return 5;
            case 'Difficile':
                return 10;
            case 'Impossible':
                return 20;
        }
    }

    private function chooseHero()
    { // Cette fonction permet de choisir un héros aléatoirement
        $this->hero = $this->hero[array_rand($this->hero)];  // array_rand en PHP est utilisée pour choisir un ou plusieurs éléments aléatoires à partir d'un tableau.
    }

    private function chooseEnemy()
    { // Cette fonction permet de choisir un ennemi aléatoirement
        return $this->ennemis[array_rand($this->ennemis)]; // array_rand en PHP est utilisée pour choisir un ou plusieurs éléments aléatoires à partir d'un tableau.
    }

    private function chooseDifficulty()
    { // Cette fonction permet de choisir un niveau de difficulté aléatoirement
        $difficultes = ['Facile', 'Difficile', 'Impossible']; // On crée un tableau contenant les 3 niveaux de difficulté
        $this->level = $difficultes[array_rand($difficultes)]; // array_rand($difficulties) renvoie une clé aléatoire du tableau $difficulties
    }

    private function resultatPartie()
    { // Cette fonction permet d'afficher le résultat de la partie
        if (!empty($this->hero) && $this->hero[0]->getBilles() > 0) { // Si le héros n'est pas vide et que le nombre de billes du héros est supérieur à 0, alors le héros a gagné
            echo "{$this->hero[0]->getName()} a gagné la partie ! Félicitation !\n";
            echo "{$this->hero[0]->getName()} a gagné 45.6 billion d'argent South Coréen !\n";
        } else {
            echo "Fin de partie. {$this->hero[0]->getName()} a perdu.\n"; // Sinon le héros a perdu
        }
    }
}

abstract class Characters //abstract permet de créer une classe abstraite c'est à dire une classe qui ne peut pas être instanciée
{ // Cette classe permet de créer les personnages du jeu
    private $name;
    private $billes;
    private $win;
    private $loose;
    private $cri;

    public function __construct($name, $billes, $win, $loose, $cri)
    { // Constructeur permettant de créer un nouveau personnage c'est à dire son nom, son nombre de billes, ses victoires, ses défaites et son cri
        $this->name = $name;
        $this->billes = $billes;
        $this->win = $win;
        $this->loose = $loose;
        $this->cri = $cri;
    }

    public function getName()
    { // Cette fonction permet de récupérer le nom du personnage
        return $this->name;
    }

    public function getBilles()
    { // Cette fonction permet de récupérer le nombre de billes du personnage
        return $this->billes;
    }

    public function getVictoire()
    { // Celle-ci permet de récupérer le nombre de victoires du personnage
        return $this->win;
    }

    public function getPerdu()
    { // Celle-ci permet de récupérer le nombre de défaites du personnage
        return $this->loose;
    }

    public function getCri()
    { // Celle-ci permet de récupérer le cri du personnage
        return $this->cri;
    }

    public function setBilles($newBilles)
    { // Cette fonction permet de modifier le nombre de billes du personnage
        $this->billes = $newBilles; // On affecte la nouvelle valeur du nombre de billes à la variable $billes
    }
}

class Hero extends Characters
{ // Cette classe permet de créer les héros du jeu, extends Characters permet de récupérer les propriétés de la classe Characters
    private $choice; // On crée une variable $choice

    public function __construct($name, $billes, $win, $loose, $cri)
    {
        parent::__construct($name, $billes, $win, $loose, $cri); // parent::__construct permet de récupérer les propriétés de la classe Characters
    }

    public function makeChoice($isEven, $triche = false)
    { // Cette fonction permet de faire un choix entre pair ou impair
        $this->choice = rand(0, 1) == 0 ? 'pair' : 'impair'; // rand(0, 1) == 0 ? 'pair' : 'impair' permet de choisir aléatoirement entre pair ou impair

        if ($triche) { // Si $triche est vrai alors on modifie le choix du héros
            $this->choice = $isEven ? 'pair' : 'impair';
        }
    }

    public function checkChoice($isEven)
    { // £isEven permet de vérifier si le nombre de billes de l'ennemi est pair ou impair
        return $this->choice === 'pair' && $isEven || $this->choice === 'impair' && !$isEven; // Si le choix du héros est pair et que le nombre de billes de l'ennemi est pair ou si le choix du héros est impair et que le nombre de billes de l'ennemi est impair alors le héros a gagné
    }

    public function applyBonus()
    { // Cette fonction permet d'appliquer le bonus du héros
        $this->setBilles($this->getBilles() + $this->getVictoire()); // On ajoute le nombre de billes du héros et le bonus du héros au nombre de billes du héros
    }

    public function applyMalus()
    { //  Cette fonction permet d'appliquer le malus du héros
        $this->setBilles($this->getBilles() - $this->getPerdu()); // On soustrait le nombre de billes du héros et le malus du héros au nombre de billes du héros
    }
}

class Ennemi extends Characters
{ // Cette classe permet de créer les ennemis du jeu, extends Characters permet de récupérer les propriétés de la classe Characters
    private $age;

    public function __construct($name, $billes, $age)
    {
        parent::__construct($name, $billes, 0, 0, 'Huh?'); // :: permet d'accéder à une propriété ou une méthode statique d'une classe
        $this->age = $age;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function checkBilles()
    {
        return $this->getBilles() % 2 == 0; // Si le nombre de billes de l'ennemi est pair alors on retourne true sinon on retourne false
    }

    private function generateKoreanNom()
    {
        $koreanNoms = ["Seo-joon", "Ae-sook", "Kyung-ho", "Yoo-jin", "Chan-woo", "Eun-mi", "Dong-ha", "Min-ah", "Hyun-woo", "Mi-sook", "Ji-eun", "Tae-hyun"];
        return $koreanNoms[array_rand($koreanNoms)]; // ici array_rand($koreanNoms) renvoie une clé aléatoire du tableau $koreanNoms c'est à dire un nom coreen aléatoire
    }
}

class Utils
{ // Cette classe permet de créer des fonctions utilitaires
    public static function generateRandomNumber($min, $max)
    { // Cette fonction permet de générer un nombre aléatoire,($min, $max) permet de définir un intervalle de nombre aléatoire
        return rand($min, $max); // rand($min, $max) permet de générer un nombre aléatoire entre $min et $max
    }

    public static function generateNumber($min, $max)
    { // Cette fonction permet de générer un nombre aléatoire,($min, $max) permet de définir un intervalle de nombre aléatoire
        return rand($min, $max); // rand($min, $max) permet de générer un nombre aléatoire entre $min et $max
    }
}
?>