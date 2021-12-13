<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('publishDate')
            ->add('description')
            ->add('coverFile', VichFileType::class, [
                'label' => ' The book',
                
            ])
            ->add('coverimage', VichFileType::class, [
                'label' => ' The image of book',
                
            ])
            ->add('updatedAt')
            ->add('quantity')
            ->add('section')
            ->add('isbn')
            ->add('category',EntityType::class,[
                'class'=>Category::class,
                'choice_label'=>'title',
                'multiple'=>false,
                
            ])
            ->add('submit',SubmitType::class,['attr' => ['class' => 'btn btn-default pull-right']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
