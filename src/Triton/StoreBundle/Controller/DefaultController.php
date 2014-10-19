<?php

namespace Triton\StoreBundle\Controller;

use Triton\StoreBundle\Entity\Category;
use Triton\StoreBundle\Entity\Product;
use Triton\StoreBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TritonStoreBundle:Default:index.html.twig', array('name' => $name));
    }

    public function createAction(Request $request)
	{
    	
		$product = new Product();
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	$cats = $em->getRepository('TritonStoreBundle:Category')->findAllOrderedByName();

    	var_dump($cats);

    	$form = $this->createFormBuilder($product)
        	->add('name', 'text')
        	->add('description', 'textarea')
        	->add('price', 'money')
        	->add('category', 'choice', array(
    	'choices'   => $cats
    ))
        	->getForm();
        	

		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
			    $em->persist($product);
	    		$em->flush();
	    	}
		
		        // выполняем прочие	 действие, например, сохраняем задачу в базе данных	
			return $this->redirect($this->generateUrl('task_success'));
				
		}
		return $this->render('TritonStoreBundle:Default:create.html.twig', array('form' => $form->createView(),));
	}

	public function showAction($id)
	{
    	$em = $this->getDoctrine()->getEntityManager();
		$product = $em->getRepository('TritonStoreBundle:Product')
			-> findOneByIdJoinedToCategory($id);

		$categoryName = $product->getCategory()->getName();	
			
    	return $this->render('TritonStoreBundle:Default:show.html.twig', array('name' => $product->getName(), 'description' => $product->getDescription(), 'category' => $categoryName));

	}

	public function showAllAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
		$products = $em->getRepository('TritonStoreBundle:Product')
            ->findAllOrderedByName();
        return $this->render('TritonStoreBundle:Default:showAll.html.twig', array('products' => $products ));
    }

    public function showPriceAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
		$products = $em->getRepository('TritonStoreBundle:Product')
			-> findAllBigPrice();
        return $this->render('TritonStoreBundle:Default:showAll.html.twig', array('products' => $products ));
    }

    public function newAction(Request $request)
    {
        // создаём задачу и присваиваем ей некоторые начальные данные для примера
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->getForm();

        return $this->render('TritonStoreBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }	

}
