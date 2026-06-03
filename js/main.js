// ============================================================
// main.js — JavaScript côté navigateur
// Gardé très simple : uniquement les interactions visuelles
// qui ne nécessitent pas le serveur PHP.
// ============================================================

// ── Basculer entre les onglets Connexion / Inscription ───
// Cette fonction est appelée par les boutons dans login.php
function afficherOnglet(onglet) {

    // On masque les deux formulaires
    document.getElementById('form-connexion').style.display   = 'none';
    document.getElementById('form-inscription').style.display = 'none';

    // On retire la classe "actif" des deux onglets
    document.getElementById('onglet-connexion').classList.remove('actif');
    document.getElementById('onglet-inscription').classList.remove('actif');

    // On affiche le bon formulaire et on active le bon onglet
    document.getElementById('form-' + onglet).style.display = 'block';
    document.getElementById('onglet-' + onglet).classList.add('actif');
}
