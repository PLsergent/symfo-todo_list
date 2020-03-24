<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{    
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index()
    {
        $entities = [];
        $em = $this->getDoctrine()->getManager();
        $meta = $em->getMetadataFactory()->getAllMetadata();

        function camel_to_snake ( $input )
        {
            if ( preg_match ( '/[A-Z]/', $input ) === 0 ) { return $input; }
            $pattern = '/([a-z])([A-Z])/';
            $r = strtolower ( preg_replace_callback ( $pattern, function ($a) {
                return $a[1] . "_" . strtolower ( $a[2] ); 
            }, $input ) );
            return $r;
        }
        
        foreach ($meta as $m) {
            $ent = camel_to_snake(explode("\\", $m->getName())[2]);
            $entities[] = $ent;
        }

        return $this->render('admin/index.html.twig', [
            'entities' => $entities
        ]);
    }
}
