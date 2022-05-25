<?php

namespace App\Controller;

use App\Helper\DocumentationHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentationController
 * @package App\Controller
 */
class DocumentationController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/documentation', name: 'documentation')]
    public function index(): Response
    {
        return $this->render('documentation/index.html.twig', [
            'links' => DocumentationHelper::getFiles()
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    #[Route('/documentation/{slug}', name: 'documentation.show')]
    public function show(string $slug): Response
    {
        return $this->render('documentation/show.html.twig', [
            'title' => str_replace('-', ' ', $slug),
            'content' => DocumentationHelper::getContent(array(DocumentationHelper::fileName($slug, DocumentationHelper::getFilesInFolder())))
        ]);
    }

    /**
     * @param string $category
     * @param string $slug
     * @return Response
     */
    #[Route('/documentation/{category}/{slug}', name: 'documentation.showInCategory')]
    public function showInCategory(string $category, string $slug): Response
    {
        $categoryName = DocumentationHelper::fileName(ucfirst($category), DocumentationHelper::getFilesInFolder());
        $fileName = DocumentationHelper::fileName($slug, DocumentationHelper::getFilesInFolder(array($categoryName)));
        $content = DocumentationHelper::getContent(array($categoryName, $fileName));

        return $this->render('documentation/show.html.twig', [
            'title' => str_replace('-', ' ', $slug),
            'content' => $content
        ]);
    }
}