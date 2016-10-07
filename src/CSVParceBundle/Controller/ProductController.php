<?php

namespace CSVParceBundle\Controller;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CSVParceBundle\Entity\Product;
use CSVParceBundle\Form\ProductType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\View\View as RView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProductController extends Controller
{
    /**
     * @Rest\View
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Return all products.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   },
     *   output = "CSVParceBundle\Entity\Product"
     * )
     * @return array
     */

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('CSVParceBundle:Product')->findAll();

        return array(
            'products' => $products,
        );

    }

    /**
     * Finds and displays a Product entity.
     *
     * @param Product $product
     *
     * @return Product $product
     *
     * @Rest\View
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Return  product by id.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when product not founded",
     *   },
     *   output = "CSVParceBundle\Entity\Product"
     * )
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * Create a new Product entity.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Create a new product",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *   },
     *   input ="CSVParceBundle\Form\ProductType" ,
     * )
     *
     */
    public function newAction()
    {
        return $this->processForm(new Product());

    }

    /**
     * Edit an existing Product entity.
     *
     * @ApiDoc(
     *
     *   description = "Edit product by id",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when product not founded",
     *   },
     *   input ="CSVParceBundle\Form\ProductType"
     * )
     *
     */
    public function editAction(Product $product)
    {

        return $this->processForm($product);

    }

    /**
     *
     * Delete product entity by id
     *
     * @Rest\View(statusCode=204)
     *
     * @param Product $product
     *
     * @ApiDoc(
     *
     *   description = "Delete product by id",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when product not founded",
     *   },
     * )
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
    }

    /**
     * Delete mane entities from array
     *
     * @Rest\View(statusCode=204)
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Input json format [id,id,id,...]. Delete product by id.",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when product not founded",
     *   },
     * )
     */
    public function allDeleteAction()
    {
        $content = $this->getRequest()->getContent();

        $products = json_decode($content);
        $em = $this->getDoctrine()->getManager();

        foreach ($products as $product_id) {

            $product = $em->getRepository('CSVParceBundle:Product')->findOneById($product_id);
            $em->remove($product);
        }
        $em->flush();
    }

    /**
     * Edit an existing Product entity from array.
     *
     * @Rest\View(statusCode=204)
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "input format
     *     {products: [
     * {
     * id: ,
     * product_name: ,
     * price: ,
     * stock: ,
     * description: ,
     * discontinued:
     * }
     * ]}",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when product not founded",
     *   },
     * )
     *
     */
    public function multiEditAction()
    {
        $serialayz = $this->get('jms_serializer');
        $content = $this->getRequest()->getContent();

        $products = $serialayz->deserialize($content, 'array', 'json');

        $em = $this->getDoctrine()->getManager();

        foreach ($products['products'] as $product) {

            $product = $serialayz->fromArray($product, 'CSVParceBundle\Entity\Product');

            /**@var Product $prod */
            $prod = $em->getRepository('CSVParceBundle:Product')->find($product->getId());

            if ($product->getDiscontinued()) {
                $product->setDiscontinuedDate(new \DateTime('now'));
            }
            $prod->setProductName($product->getProductName());
            $prod->setDescription($product->getDescription());
            $prod->setDiscontinued($product->getDiscontinued());
            $prod->setPrice($product->getPrice());
            $prod->setStock($product->getStock());
            $prod->setDiscontinuedDate($product->getDiscontinuedDate());

            $em->persist($prod);

        }
        $em->flush();

        $response = new Response();

        return $response->setContent('OK');
    }

    private function processForm(Product $product)
    {
        $statusCode = $product->getId() ? 204 : 201;

        $form = $this->createForm(new ProductType(), $product);

        $form->bind($this->getRequest());

        $validator = $this->get('validator');
        $errors = $validator->validate($product);

        if ($product->getDiscontinued()) {
            $product->setDiscontinuedDate(new \DateTime('now'));
        }

        if (!(count($errors) > 0)) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->headers->set('Location',
                $this->generateUrl(
                    'product_show', array('id' => $product->getId()), true
                )
            );
            return $response;
        }

        return RView::create($form, 400);
    }
}

