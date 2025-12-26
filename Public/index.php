<?php

require_once __DIR__ . '/../App/Database/Database.php';
require_once __DIR__ . '/../App/Models/Member.php';
require_once __DIR__ . '/../App/Models/Project.php';
require_once __DIR__ . '/../App/Models/ShortProject.php';
require_once __DIR__ . '/../App/Models/LongProject.php'; 
require_once __DIR__ . '/../App/Models/Activity.php';

$pdo = Database::getConnection();

Member::setPDO($pdo);
ShortProject::setPDO($pdo);
LongProject::setPDO($pdo);
Activity::setPDO($pdo);

echo "╔════════════════════════════════════╗\n";
echo "║    METIS - Gestion de Projets      ║\n";
echo "╚════════════════════════════════════╝\n\n";

while (true) {
    echo "\n=== MENU PRINCIPAL ===\n";
    echo "1. Gestion des Membres\n";
    echo "2. Gestion des Projets\n";
    echo "3. Gestion des Activités\n";
    echo "0. Quitter\n";
    echo "Choix: ";
    
    $choice = trim(fgets(STDIN));
    
    if ($choice === '0') {
        echo "Au revoir!\n";
        break;
    }
    
    try {
        switch ($choice) {
            case '1':
                menuMembres();
                break;
            case '2':
                menuProjets();
                break;
            case '3':
                menuActivites();
                break;
            default:
                echo "Choix invalide.\n";
        }
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
}

// ========== MEMBRES ==========
function menuMembres() {
    while (true) {
        echo "\n--- GESTION MEMBRES ---\n";
        echo "1. Créer membre\n";
        echo "2. Lister membres\n";
        echo "3. Voir détails membre\n";
        echo "4. Modifier membre\n";
        echo "5. Supprimer membre\n";
        echo "0. Retour\n";
        echo "Choix: ";
        
        $choice = trim(fgets(STDIN));
        
        if ($choice === '0') break;
        
        try {
            switch ($choice) {
                case '1':
                    echo "Nom: ";
                    $nom = trim(fgets(STDIN));
                    echo "Email: ";
                    $email = trim(fgets(STDIN));
                    
                    $id = Member::save(['name' => $nom, 'email' => $email]);
                    echo "✅ Membre créé avec ID: $id\n";
                    break;
                    
                case '2':
                    $membres = Member::findAll();
                    if (!$membres) {
                        echo "Aucun membre.\n";
                        break;
                    }
                    echo "\n📋 Liste des membres:\n";
                    foreach ($membres as $m) {
                        echo "#{$m['id']} - {$m['name']} ({$m['email']})\n";
                    }
                    break;
                    
                case '3':
                    echo "ID du membre: ";
                    $id = (int)trim(fgets(STDIN));
                    $m = Member::findById($id);
                    if (!$m) {
                        echo "Membre introuvable.\n";
                    } else {
                        echo "\n👤 Détails:\n";
                        echo "ID: {$m['id']}\n";
                        echo "Nom: {$m['name']}\n";
                        echo "Email: {$m['email']}\n";
                        echo "Créé le: {$m['created_at']}\n";
                    }
                    break;
                    
                case '4':
                    echo "ID du membre à modifier: ";
                    $id = (int)trim(fgets(STDIN));
                    $m = Member::findById($id);
                    if (!$m) {
                        echo "Membre introuvable.\n";
                        break;
                    }
                    
                    echo "Nom [{$m['name']}]: ";
                    $nom = trim(fgets(STDIN));
                    if (empty($nom)) $nom = $m['name'];
                    
                    echo "Email [{$m['email']}]: ";
                    $email = trim(fgets(STDIN));
                    if (empty($email)) $email = $m['email'];
                    
                    Member::save(['id' => $id, 'name' => $nom, 'email' => $email]);
                    echo "✅ Membre modifié.\n";
                    break;
                    
                case '5':
                    echo "ID du membre à supprimer: ";
                    $id = (int)trim(fgets(STDIN));
                    Member::delete($id);
                    echo "✅ Membre supprimé.\n";
                    break;
                    
                default:
                    echo "Choix invalide.\n";
            }
        } catch (\Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "\n";
        }
    }
}

// ========== PROJETS ==========
function menuProjets() {
    while (true) {
        echo "\n--- GESTION PROJETS ---\n";
        echo "1. Créer projet court\n";
        echo "2. Créer projet long\n";
        echo "3. Lister tous les projets\n";
        echo "4. Projets d'un membre\n";
        echo "5. Supprimer projet\n";
        echo "0. Retour\n";
        echo "Choix: ";
        
        $choice = trim(fgets(STDIN));
        
        if ($choice === '0') break;
        
        try {
            switch ($choice) {
                case '1':
                    echo "Membre ID: ";
                    $membreId = (int)trim(fgets(STDIN));
                    echo "Titre: ";
                    $titre = trim(fgets(STDIN));
                    echo "Description: ";
                    $desc = trim(fgets(STDIN));
                    
                    $id = ShortProject::save([
                        'title' => $titre,
                        'descreption' => $desc,
                        'type' => 'court',
                        'member_id' => $membreId
                    ]);
                    
                    echo "✅ Projet court créé (ID: $id)\n";
                    break;
                    
                case '2':
                    echo "Membre ID: ";
                    $membreId = (int)trim(fgets(STDIN));
                    echo "Titre: ";
                    $titre = trim(fgets(STDIN));
                    echo "Description: ";
                    $desc = trim(fgets(STDIN));
                    
                    $id = LongProject::save([
                        'title' => $titre,
                        'descreption' => $desc,
                        'type' => 'long',
                        'member_id' => $membreId
                    ]);
                    
                    echo "✅ Projet long créé (ID: $id)\n";
                    break;
                    
                case '3':
                    $projets = ShortProject::findAll();
                    if (!$projets) {
                        echo "Aucun projet.\n";
                        break;
                    }
                    echo "\n📁 Liste des projets:\n";
                    foreach ($projets as $p) {
                        echo "#{$p['id']} - {$p['title']} ({$p['type']}) - Membre #{$p['member_id']}\n";
                    }
                    break;
                    
                case '4':
                    echo "ID du membre: ";
                    $membreId = (int)trim(fgets(STDIN));
                    
                    $pdo = Database::getConnection();
                    $stmt = $pdo->prepare("SELECT * FROM projects WHERE member_id = ?");
                    $stmt->execute([$membreId]);
                    $projets = $stmt->fetchAll();
                    
                    if (!$projets) {
                        echo "Aucun projet pour ce membre.\n";
                        break;
                    }
                    echo "\n📁 Projets du membre #$membreId:\n";
                    foreach ($projets as $p) {
                        echo "#{$p['id']} - {$p['title']} ({$p['type']})\n";
                    }
                    break;
                    
                case '5':
                    echo "ID du projet à supprimer: ";
                    $id = (int)trim(fgets(STDIN));
                    ShortProject::delete($id);
                    echo "✅ Projet supprimé.\n";
                    break;
                    
                default:
                    echo "Choix invalide.\n";
            }
        } catch (\Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "\n";
        }
    }
}

// ========== ACTIVITÉS ==========
function menuActivites() {
    while (true) {
        echo "\n--- GESTION ACTIVITÉS ---\n";
        echo "1. Ajouter activité\n";
        echo "2. Modifier activité\n";
        echo "3. Supprimer activité\n";
        echo "4. Historique activités d'un projet\n";
        echo "0. Retour\n";
        echo "Choix: ";
        
        $choice = trim(fgets(STDIN));
        
        if ($choice === '0') break;
        
        try {
            switch ($choice) {
                case '1':
                    echo "Projet ID: ";
                    $projetId = (int)trim(fgets(STDIN));
                    echo "Titre: ";
                    $titre = trim(fgets(STDIN));
                    echo "Description: ";
                    $desc = trim(fgets(STDIN));
                    
                    $id = Activity::save([
                        'title' => $titre,
                        'descreption' => $desc,
                        'project_id' => $projetId
                    ]);
                    echo "✅ Activité créée (ID: $id)\n";
                    break;
                    
                case '2':
                    echo "ID activité à modifier: ";
                    $id = (int)trim(fgets(STDIN));
                    $a = Activity::findById($id);
                    if (!$a) {
                        echo "Activité introuvable.\n";
                        break;
                    }
                    
                    echo "Titre [{$a['title']}]: ";
                    $titre = trim(fgets(STDIN));
                    if (empty($titre)) $titre = $a['title'];
                    
                    echo "Statut [{$a['status']}] (todo/doing/done): ";
                    $status = trim(fgets(STDIN));
                    if (empty($status)) $status = $a['status'];
                    
                    Activity::save([
                        'id' => $id,
                        'title' => $titre,
                        'descreption' => $a['descreption'],
                        'status' => $status,
                        'project_id' => $a['project_id']
                    ]);
                    echo "✅ Activité modifiée.\n";
                    break;
                    
                case '3':
                    echo "ID activité à supprimer: ";
                    $id = (int)trim(fgets(STDIN));
                    Activity::delete($id);
                    echo "✅ Activité supprimée.\n";
                    break;
                    
                case '4':
                    echo "ID du projet: ";
                    $projetId = (int)trim(fgets(STDIN));
                    $activites = Activity::findByProject($projetId);
                    
                    if (!$activites) {
                        echo "Aucune activité pour ce projet.\n";
                        break;
                    }
                    
                    echo "\n📊 Historique activités (Projet #$projetId):\n";
                    foreach ($activites as $a) {
                        echo "#{$a['id']} - {$a['title']} ({$a['status']}) - {$a['created_at']}\n";
                    }
                    break;
                    
                default:
                    echo "Choix invalide.\n";
            }
        } catch (\Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "\n";
        }
    }
}
