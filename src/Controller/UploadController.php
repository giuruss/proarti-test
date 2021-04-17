<?php

declare(strict_types=1);

namespace App\Controller;

use App\CSV\FileUploader;
use App\Form\UploadType;
use App\Interfaces\CSV\CsvManagerInterface;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload')]
    final public function import(Request $request, FileUploader $fileUploader, CsvManagerInterface $csvManager): Response
    {
        $form = $this->createForm(UploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('name')->getData();

            $importResult = null;

            if ($csvFile) {
                $csvFileName = $fileUploader->upload($csvFile);
                $this->addFlash('success', 'Fichier csv importé avec succès');
                $file = new SplFileObject($fileUploader->getTargetDirectory().'/'.$csvFileName);
                $importResult = $csvManager->import($file);
            }

            return $this->render('upload/index.html.twig', [
                'form' => $form->createView(),
                'import_result' => $importResult,
            ]);
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
