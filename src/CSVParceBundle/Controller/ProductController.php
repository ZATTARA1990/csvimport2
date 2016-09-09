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


    private function processForm(Product $product)
    {
//        $statusCode = $product->isNew() ? 201 : 204;
        $request = new Request();
        $form = $this->createForm(new ProductType(), $product);
        $form->bind($this->getRequest());

//      if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $response = new Response();
            $response->setStatusCode('201');
            $response->headers->set('Location',
                $this->generateUrl(
                    'product_show', array('id' => $product->getId()), true
                )
            );

            return $response;
//        }
//        return 'This her';
//
//        return RView::create($form, 400);
    }


}
