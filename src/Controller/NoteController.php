<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Form\SearchType;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/note")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="note_index", methods={"GET","POST"})
     */
    public function index(Request $request, NoteRepository $noteRepository): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $notes = $form->isSubmitted() && $form->isValid() 
            ? $noteRepository->search($form->get('query')->getData())
            : $noteRepository->findAllOrderedByDate();

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="note_new", methods={"GET","POST"})
     */
    public function new(Request $request, NoteRepository $noteRepository): Response
    {
        $note = new Note();
        $note->setCreatedAt(new \DateTimeImmutable());
        
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noteRepository->add($note, true);
            $this->addFlash('success', 'Note créée avec succès');
            return $this->redirectToRoute('note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note/new.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="note_show", methods={"GET"})
     */
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="note_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Note $note, NoteRepository $noteRepository): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noteRepository->add($note, true);
            $this->addFlash('success', 'Note modifiée avec succès');
            return $this->redirectToRoute('note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="note_delete", methods={"POST"})
     */
    public function delete(Request $request, Note $note, NoteRepository $noteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $noteRepository->remove($note, true);
            $this->addFlash('success', 'Note supprimée avec succès');
        }

        return $this->redirectToRoute('note_index', [], Response::HTTP_SEE_OTHER);
    }
}
