<?php

namespace App\DataFixtures;

use App\Entity\Note;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de 5 notes de démo
        $sampleNotes = [
            ['title' => 'Réunion importante', 'content' => 'Préparer la présentation pour vendredi'],
            ['title' => 'Idées projets', 'content' => 'Créer un système de gestion des tâches'],
            ['title' => 'Courses', 'content' => 'Lait, œufs, pain, fruits'],
            ['title' => 'RDV médical', 'content' => 'Le 15/09 à 14h30'],
            ['title' => 'Notes Symfony', 'content' => 'Finir le CRUD des notes']
        ];

        foreach ($sampleNotes as $sample) {
            $note = new Note();
            $note->setTitle($sample['title']);
            $note->setContent($sample['content']);
            $note->setCreatedAt(new \DateTime());
            
            $manager->persist($note);
        }

        $manager->flush();
    }
}
