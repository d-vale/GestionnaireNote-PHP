<?php

namespace ch\comem;

interface I_ApiCRUD
{
    //Création des tables
    public function creeTableUtilisateur(): bool;
    public function creeTableNotes(): bool;

    //Ajouter des données
    public function ajouteUtilisateur(Utilisateur $utilisateur): int;
    public function ajouteNotes(Notes $notes): int;

    //Modifier des données
    public function modifieUtilisateur(Utilisateur $utilisateur): bool;

    //Récupérer des données
    public function rendUtilisateur(string $nom): array;
    public function rendNotes(int $id): array;

    //Supprimer des données
    public function supprimeUtilisateur(int $id): bool;
    public function supprimeNotes(int $id): bool;

}
