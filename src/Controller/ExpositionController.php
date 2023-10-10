<?php

namespace App\Controller;

use App\Entity\Exposition;
use App\Form\ExpositionType;
use App\Repository\ExpositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpositionController extends AbstractController
{
    #[Route('/', name: 'app_exposition')]
    public function index(ExpositionRepository $expositionRepository): Response
    {
        $getAll = $expositionRepository->findAll();
        return $this->render('exposition/index.html.twig', [
            'expositions'=> $getAll,
        ]);
    }

    #[Route('exposition/add', name: "add_exposition")]
    public function addExposition(Request $request, EntityManagerInterface $em): Response
    {
        $expo = new Exposition();
        $expo->setImage("https://picsum.photos/id/" . random_int(1, 200) .  "/200/300");
        $form = $this->createForm(ExpositionType::class, $expo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($expo);
            $em->flush();
            return $this->redirectToRoute('app_exposition');
        }
        return $this->render('exposition/add.html.twig', [
            'form_add_expo' => $form->createView(),
        ]);
    }

    #[Route('exposition/edit/{id}', name: "edit_exposition")]
    public function edit(int $id, ExpositionRepository $expositionRepository, Request $request, EntityManagerInterface $em)
    {
        $expo = $expositionRepository->find($id);
        $form = $this->createForm(ExpositionType::class, $expo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($expo);
            $em->flush();
            return $this->redirectToRoute('app_exposition');
        }
        return $this->render('exposition/edit.html.twig', [
            'form_add_expo' => $form->createView(),
        ]);
    }

    #[Route('exposition/delete/{id}', name: "delete_exposition")]
    public function delete(int $id, EntityManagerInterface $em, ExpositionRepository $expositionRepository)
    {
        $exposition = $expositionRepository->find($id);
        if ($exposition) {
            $em->remove($exposition);
            $em->flush();

            return $this->redirectToRoute('app_exposition');
        }
        return $this->render('exposition/index.html.twig', [
            'controller_name' => 'ExpositionController',
        ]);
    }

}

