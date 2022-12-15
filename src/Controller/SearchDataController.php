<?php

namespace App\Controller;

use App\Entity\SearchData;
use App\Form\SearchData1Type;
use App\Repository\SearchDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search/data')]
class SearchDataController extends AbstractController
{
    #[Route('/', name: 'app_search_data_index', methods: ['GET'])]
    public function index(SearchDataRepository $searchDataRepository): Response
    {
        return $this->render('search_data/index.html.twig', [
            'search_datas' => $searchDataRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_search_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SearchDataRepository $searchDataRepository): Response
    {
        $searchDatum = new SearchData();
        $form = $this->createForm(SearchData1Type::class, $searchDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchDataRepository->save($searchDatum, true);

            return $this->redirectToRoute('app_search_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('search_data/new.html.twig', [
            'search_datum' => $searchDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_search_data_show', methods: ['GET'])]
    public function show(SearchData $searchDatum): Response
    {
        return $this->render('search_data/show.html.twig', [
            'search_datum' => $searchDatum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_search_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SearchData $searchDatum, SearchDataRepository $searchDataRepository): Response
    {
        $form = $this->createForm(SearchData1Type::class, $searchDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchDataRepository->save($searchDatum, true);

            return $this->redirectToRoute('app_search_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('search_data/edit.html.twig', [
            'search_datum' => $searchDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_search_data_delete', methods: ['POST'])]
    public function delete(Request $request, SearchData $searchDatum, SearchDataRepository $searchDataRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$searchDatum->getId(), $request->request->get('_token'))) {
            $searchDataRepository->remove($searchDatum, true);
        }

        return $this->redirectToRoute('app_search_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
