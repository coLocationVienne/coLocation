<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Comment;

class CommentTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $data = [
            'id_avis' => 1,
            'note' => 4.5,
            'date_' => '2026-07-31',
            'commentaire' => 'Très bon logement, propriétaire sérieux.',
            'id_annonce' => 10,
            'id_utilisateur' => 3,
            'id_utilisateur_1' => 2,
            'author_name' => 'Jean Dupont',
        ];

        $comment = new Comment($data);

        $this->assertEquals(1, $comment->getId());
        $this->assertEquals(4.5, $comment->getNote());
        $this->assertEquals('2026-07-31', $comment->getDate());
        $this->assertEquals('Très bon logement, propriétaire sérieux.', $comment->getCommentaire());
        $this->assertEquals(10, $comment->getIdAnnonce());
        $this->assertEquals(3, $comment->getIdUtilisateur());
        $this->assertEquals(2, $comment->getIdUtilisateur1());
        $this->assertEquals('Jean Dupont', $comment->getAuthorName());
    }

    public function testDefaultAuthorName(): void
    {
        $data = [
            'note' => 3,
            'date_' => '2026-07-31',
            'commentaire' => 'Commentaire sans auteur.',
            'id_annonce' => 10,
            'id_utilisateur' => 3,
            'id_utilisateur_1' => 2,
        ];

        $comment = new Comment($data);

        $this->assertEquals('Utilisateur', $comment->getAuthorName());
    }
}