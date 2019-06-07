<?php

namespace AppBundle\Form;

use AppBundle\Entity\Servers;
use AppBundle\Enum\ServerTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phpseclib\Net\SSH2;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class ServersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices_as_values' => true,
                'placeholder' => 'Choose an option',
                'attr' => ['class' => 'select2 form-control modelajax'],
                'choices' => ServerTypeEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                    return ServerTypeEnum::getTypeName($choice);
                },
            ])
                 ->add('link')
                 ->add('ip')
                 ->add('sshUser')
                 ->add('sshPassword', PasswordType::class)
                 ->add('sshPort')
                 ->add('http')
                 ->add('portHttp');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Servers',
            'constraints' => [
                new Callback([
                    'callback' => [$this, 'CheckServer'],
                ]),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_servers';
    }

    public function CheckServer(Servers $server, ExecutionContextInterface $context)
    {
        $connection = new SSH2($server->getIp(), $server->getSshPort());
        try {
            $connection->login($server->getSshUser(), $server->getSshPassword());
        } catch (\Exception $exception) {
            $context->buildViolation($exception->getMessage())
                ->atPath('ip')
                ->addViolation();
            $context->buildViolation($exception->getMessage())
                ->atPath('sshPort')
                ->addViolation();
        }
        if (!$connection->isAuthenticated()) {
            $context->buildViolation('Invalid login or password')
                ->atPath('sshUser')
                ->addViolation();
            $context->buildViolation('Invalid login or password')
                ->atPath('sshPassword')
                ->addViolation();
        }
    }


}
