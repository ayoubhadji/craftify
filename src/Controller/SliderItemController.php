<?php

namespace App\Controller;

use App\Entity\SliderItem;
use App\Form\SliderItemType;
use App\Repository\SliderItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Foire;



#[Route('/slider/item')]
final class SliderItemController extends AbstractController
{
    
    #[Route(name: 'app_slider_item_index', methods: ['GET'])]
    public function index(SliderItemRepository $sliderItemRepository): Response
    {
        return $this->render('slider_item/index.html.twig', [
            'slider_items' => $sliderItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_slider_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $sliderItem = new SliderItem();
        $form = $this->createForm(SliderItemType::class, $sliderItem);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload failure
                }
    
                $sliderItem->setImagePath('uploads/images/'.$newFilename);
            }
    
            // Here, the form has already set the 'foire' field based on the user's selection
            $entityManager->persist($sliderItem);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_slider_item_index');
        }
    
        return $this->render('slider_item/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_slider_item_show', methods: ['GET'])]
    public function show(SliderItem $sliderItem): Response
    {
        return $this->render('slider_item/show.html.twig', [
            'slider_item' => $sliderItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_slider_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SliderItem $sliderItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SliderItemType::class, $sliderItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_slider_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('slider_item/edit.html.twig', [
            'slider_item' => $sliderItem,
            'form' => $form,
        ]);
    }

// Example in the delete method
#[Route('/{id}', name: 'app_slider_item_delete', methods: ['POST'])]
public function delete(Request $request, SliderItem $sliderItem, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $sliderItem->getId(), $request->get('_token'))) {
        
        // Optional: delete related Foire if needed
        $foire = $sliderItem->getFoire();
        if ($foire) {
            $entityManager->remove($foire); // Only remove if needed
        }

        // Remove the slider item
        $entityManager->remove($sliderItem);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_slider_item_index', [], Response::HTTP_SEE_OTHER);
}


}
