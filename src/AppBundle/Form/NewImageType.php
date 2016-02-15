<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\ImageType;
use AppBundle\Entity\Image;

class NewImageType extends ImageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
        ->remove('imageName')
        ->add('imageFile', 'comur_image', array(
            'label'=>false,
            'uploadConfig' => array(
                        'uploadUrl' => 'images/avatars',       // required - see explanation below (you can also put just a dir path)
                        'webDir' => 'web/images/avatars',               // required - see explanation below (you can also put just a dir path)
                        'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                         'showLibrary' => false,                      //optional
            'saveOriginal' => false           //optional
                        ),
            'cropConfig' => array(
                'minWidth' => 200,
                'minHeight' => 200,
                        'aspectRatio' => true,              //optional
                        'cropRoute' => 'comur_api_crop',    //optional
                        'forceResize' => false,             //optional
                        'thumbs' => array(                  //optional
                            array(
                                'maxWidth' => 400,
                                'maxHeight' => 400,
                                'useAsFieldImage' => true  //optional
                                )
                            )
                        )
            ));

}
}



