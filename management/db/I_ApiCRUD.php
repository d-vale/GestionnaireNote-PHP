<?php

namespace ch\comem;

interface I_ApiCRUD
{
    public function creeTableUtilisateur(): bool;
    public function ajouteUtilisateur(Utilisateur $utilisateur): int;
    public function modifieUtilisateur(Utilisateur $utilisateur): bool;
    public function rendUtilisateur(string $nom): array;
    public function supprimeUtilisateur(int $id): bool;
}
