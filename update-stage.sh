#!/bin/bash

# Répertoire de l'environnement de mise en scène (le répertoire courant)
stageDir="$(pwd)"

# Fonction de mise à jour de l'environnement de mise en scène
update_stage() {
    echo "Mise à jour de l'environnement de mise en scène dans $stageDir..."

    # Naviguer vers le répertoire de l'environnement de mise en scène
    cd "$stageDir" || { echo "Erreur : Impossible de naviguer vers $stageDir"; exit 1; }

    # Arrêter les conteneurs
    docker-compose -f docker-compose.stage.yml down

    # Tirer les dernières modifications du dépôt Git
    git pull origin Stage

    # Rebuild et redémarrer les conteneurs
    docker-compose -f docker-compose.stage.yml up --build -d

    echo "Environnement de mise en scène mis à jour avec succès."
}

# Mettre à jour l'environnement de mise en scène
update_stage