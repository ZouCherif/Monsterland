<?php
    namespace App\Form\Type;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Entity\Monstre;
    use App\Entity\Royaume;
    class MonstreType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nom', TextType::class)
        ->add('type', TextType::class)
        ->add('puissance', IntegerType::class)
        ->add('taille', IntegerType::class)
        ->add('royaume', EntityType::class, ['class' => Royaume::class, 'choice_label' => 'nom']);
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
        'data_class' => Monstre::class,
        ));
        }
    }   
