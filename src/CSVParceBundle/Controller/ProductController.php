<?php

namespace CSVParceBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CSVParceBundle\Entity\Product;
use CSVParceBundle\Form\ProductType;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\View\View as RView;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{

    /**
     * @Rest\View
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
     * @Rest\View
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('CSVParceBundle:Product')->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Unable to find product.');
        }
        return array('product' => $product);
    }


    /**
     * Creates a new Product entity.
     *
     */
    public function newAction()
    {
        return $this->processForm(new Product());

    }


    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editAction(Product $product)
    {
        return $this->processForm($product);
    }

    /**
     * @Rest\View(statusCode=204)
     */

    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

    }

    /**
     * @Rest\View(statusCode=204)
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
     * Displays a form to edit an existing Product entities.
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
            dump($product);
            die();
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

        return $response->setContent('ok');
    }

    private function processForm(Product $product)
    {
        $serialayz = $this->get('jms_serializer');



        $statusCode = $product->getId() ? 204 : 201;


        $form = $this->createForm(new ProductType(), $product);

        $product = $serialayz->deserialize($this->getRequest()->getContent(), 'array', 'json');
       
        $product = $serialayz->fromArray($product['product'], 'CSVParceBundle\Entity\Product');

        $form->bind($product);


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
