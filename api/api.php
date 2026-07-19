<?php
// api/api.php - API santral

define('DATA_FILE', '../data/livres.json');
define('PDF_DIR', '../pdf/');

// Fonksyon POO pou Liv
class Livre {
    private $id;
    private $titre;
    private $auteur;
    private $annee;
    private $maison_edition;
    private $categorie;
    private $disponibilite;
    private $pdf;
    private $description;
    private $nb_pages;

    public function __construct($id, $titre, $auteur, $annee, $maison_edition, $categorie, $disponibilite, $pdf = '', $description = '', $nb_pages = 0) {
        $this->id = $id;
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->annee = $annee;
        $this->maison_edition = $maison_edition;
        $this->categorie = $categorie;
        $this->disponibilite = $disponibilite;
        $this->pdf = $pdf;
        $this->description = $description;
        $this->nb_pages = $nb_pages;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'auteur' => $this->auteur,
            'annee' => $this->annee,
            'maison_edition' => $this->maison_edition,
            'categorie' => $this->categorie,
            'disponibilite' => $this->disponibilite,
            'pdf' => $this->pdf,
            'description' => $this->description,
            'nb_pages' => $this->nb_pages
        ];
    }
}

function lireLivres() {
    if (!file_exists(DATA_FILE)) return [];
    $contenu = file_get_contents(DATA_FILE);
    return json_decode($contenu, true) ?? [];
}

function ecrireLivres($livres) {
    file_put_contents(DATA_FILE, json_encode($livres, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function genererId($livres) {
    if (empty($livres)) return 1;
    return max(array_column($livres, 'id')) + 1;
}

// Vérifier si fichier PDF existe
function pdfExiste($nom_pdf) {
    return !empty($nom_pdf) && file_exists(PDF_DIR . $nom_pdf);
}

// Vérifier si l'utilisateur est connecté (pour la lecture PDF)
function estConnecte() {
    session_start();
    return isset($_SESSION['user']) || isset($_SESSION['admin']);
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => 'Action non reconnue', 'data' => null];

switch ($action) {
    case 'liste':
        $livres = lireLivres();
        $response = ['success' => true, 'message' => 'Liste récupérée', 'data' => $livres];
        break;

    case 'ajouter':
        if ($method === 'POST') {
            $titre = htmlspecialchars(trim($_POST['titre'] ?? ''));
            $auteur = htmlspecialchars(trim($_POST['auteur'] ?? ''));
            $annee = intval($_POST['annee'] ?? 0);
            $maison_edition = htmlspecialchars(trim($_POST['maison_edition'] ?? ''));
            $categorie = htmlspecialchars(trim($_POST['categorie'] ?? 'Autre'));
            $disponibilite = htmlspecialchars(trim($_POST['disponibilite'] ?? 'Disponible'));
            $pdf = htmlspecialchars(trim($_POST['pdf'] ?? ''));
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            $nb_pages = intval($_POST['nb_pages'] ?? 0);

            if (empty($titre) || empty($auteur) || $annee <= 0 || empty($maison_edition)) {
                $response = ['success' => false, 'message' => 'Tous les champs sont requis'];
                break;
            }

            $livres = lireLivres();
            $nouveauLivre = new Livre(genererId($livres), $titre, $auteur, $annee, $maison_edition, $categorie, $disponibilite, $pdf, $description, $nb_pages);
            $livres[] = $nouveauLivre->toArray();
            ecrireLivres($livres);
            $response = ['success' => true, 'message' => 'Livre ajouté avec succès', 'data' => $nouveauLivre->toArray()];
        }
        break;

    case 'modifier':
        if ($method === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $titre = htmlspecialchars(trim($_POST['titre'] ?? ''));
            $auteur = htmlspecialchars(trim($_POST['auteur'] ?? ''));
            $annee = intval($_POST['annee'] ?? 0);
            $maison_edition = htmlspecialchars(trim($_POST['maison_edition'] ?? ''));
            $categorie = htmlspecialchars(trim($_POST['categorie'] ?? 'Autre'));
            $disponibilite = htmlspecialchars(trim($_POST['disponibilite'] ?? 'Disponible'));
            $pdf = htmlspecialchars(trim($_POST['pdf'] ?? ''));
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            $nb_pages = intval($_POST['nb_pages'] ?? 0);

            if ($id <= 0 || empty($titre) || empty($auteur) || $annee <= 0 || empty($maison_edition)) {
                $response = ['success' => false, 'message' => 'Tous les champs sont requis'];
                break;
            }

            $livres = lireLivres();
            $trouve = false;
            foreach ($livres as &$livre) {
                if ($livre['id'] === $id) {
                    $livre['titre'] = $titre;
                    $livre['auteur'] = $auteur;
                    $livre['annee'] = $annee;
                    $livre['maison_edition'] = $maison_edition;
                    $livre['categorie'] = $categorie;
                    $livre['disponibilite'] = $disponibilite;
                    $livre['pdf'] = $pdf;
                    $livre['description'] = $description;
                    $livre['nb_pages'] = $nb_pages;
                    $trouve = true;
                    break;
                }
            }
            if ($trouve) {
                ecrireLivres($livres);
                $response = ['success' => true, 'message' => 'Livre modifié avec succès', 'data' => $livres];
            } else {
                $response = ['success' => false, 'message' => 'Livre non trouvé'];
            }
        }
        break;

    case 'supprimer':
        if ($method === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id <= 0) {
                $response = ['success' => false, 'message' => 'ID invalide'];
                break;
            }

            $livres = lireLivres();
            $trouve = false;
            foreach ($livres as $key => $livre) {
                if ($livre['id'] === $id) {
                    unset($livres[$key]);
                    $trouve = true;
                    break;
                }
            }
            if ($trouve) {
                $livres = array_values($livres);
                ecrireLivres($livres);
                $response = ['success' => true, 'message' => 'Livre supprimé avec succès', 'data' => $livres];
            } else {
                $response = ['success' => false, 'message' => 'Livre non trouvé'];
            }
        }
        break;

    case 'rechercher':
        $terme = htmlspecialchars(trim($_GET['terme'] ?? ''));
        $livres = lireLivres();
        if (empty($terme)) {
            $response = ['success' => true, 'message' => 'Tous les livres', 'data' => $livres];
        } else {
            $resultats = array_filter($livres, function($livre) use ($terme) {
                return stripos($livre['titre'], $terme) !== false ||
                       stripos($livre['auteur'], $terme) !== false ||
                       stripos($livre['categorie'], $terme) !== false ||
                       stripos($livre['maison_edition'], $terme) !== false;
            });
            $response = ['success' => true, 'message' => 'Recherche effectuée', 'data' => array_values($resultats)];
        }
        break;

    case 'details':
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $livres = lireLivres();
            foreach ($livres as $livre) {
                if ($livre['id'] === $id) {
                    $response = ['success' => true, 'message' => 'Détails du livre', 'data' => $livre];
                    break;
                }
            }
        }
        break;

    case 'pdf':
        // Vérifier si utilisateur connecté
        session_start();
        if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
            $response = ['success' => false, 'message' => 'Vous devez être connecté pour lire ce livre'];
            break;
        }

        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $livres = lireLivres();
            foreach ($livres as $livre) {
                if ($livre['id'] === $id && !empty($livre['pdf'])) {
                    $chemin_pdf = PDF_DIR . $livre['pdf'];
                    if (file_exists($chemin_pdf)) {
                        $response = [
                            'success' => true,
                            'message' => 'PDF trouvé',
                            'data' => [
                                'nom' => $livre['pdf'],
                                'chemin' => $chemin_pdf
                            ]
                        ];
                    } else {
                        $response = ['success' => false, 'message' => 'Fichier PDF non trouvé'];
                    }
                    break;
                }
            }
        } else {
            $response = ['success' => false, 'message' => 'Livre non trouvé'];
        }
        break;

    default:
        $response = ['success' => false, 'message' => 'Action inconnue'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
