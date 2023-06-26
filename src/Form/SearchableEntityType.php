<?php

namespace AppoloDev\SFToolboxBundle\Form;

use AppoloDev\SFToolbox\Doctrine\Entity\Concern\IdentifiableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

// TODO: Gros refactor
class SearchableEntityType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var class-string<object> $entityClass */
        $entityClass = $options['class'];
        if (true === $options['multiple']) {
            $builder
                ->addModelTransformer(new CallbackTransformer(
                    function (Collection $collection): array {
                        return $collection->map(function ($object) {
                            /* @var IdentifiableInterface $object */
                            return (string) $object->getId();
                        })->toArray();
                    },
                    function (?array $ids) use ($entityClass): Collection {
                        if (is_null($ids)) {
                            return new ArrayCollection([]);
                        }

                        $repository = $this->entityManager->getRepository($entityClass);

                        if (method_exists($repository, 'findIds')) {
                            $data = $repository->findIds($ids);

                            return new ArrayCollection($data);
                        }

                        return new ArrayCollection([]);
                    }
                ));
        } else {
            $builder
                ->addModelTransformer(new CallbackTransformer(
                    function (mixed $object): ?string {
                        return is_null($object) ? null : $object->getId();
                    },
                    function (?string $id) use ($entityClass): mixed {
                        if (is_null($id)) {
                            return null;
                        }

                        return $this->entityManager
                            ->getRepository($entityClass)
                            ->find($id);
                    }
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('class');
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => true,
            'choice_label' => null,
            'search' => '/search',
            'value_property' => 'id',
            'label_property' => 'title',
            'search_field_property' => ['title'],
            'render_option' => null,
            'render_item' => null,
        ]);
        $resolver->setAllowedTypes('choice_label', ['null', 'string']);
        $resolver->setAllowedTypes('search_field_property', ['null', 'array']);
        $resolver->setAllowedTypes('render_option', ['null', 'array']);
        $resolver->setAllowedTypes('render_item', ['null', 'array']);
        $resolver->setAllowedTypes('search', ['null', 'string']);
        $resolver->setAllowedTypes('value_property', ['null', 'string']);
        $resolver->setAllowedTypes('label_property', ['null', 'string']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['expanded'] = false;
        $view->vars['placeholder'] = null;
        $view->vars['placeholder_in_choices'] = false;
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['preferred_choices'] = [];
        $view->vars['choice_translation_domain'] = false;
        $view->vars['attr']['data-remote'] = $options['search'];
        $view->vars['attr']['data-value'] = $options['value_property'];
        $view->vars['attr']['data-label'] = $options['label_property'];
        $view->vars['attr']['data-search-field'] = json_encode($options['search_field_property']);

        if (!is_null($options['render_option'])) {
            $view->vars['attr']['data-render-option'] = json_encode($options['render_option']);
        }
        if (!is_null($options['render_item'])) {
            $view->vars['attr']['data-render-item'] = json_encode($options['render_item']);
        }

        if (
            true === $options['multiple']) {
            $view->vars['full_name'] .= '[]';
            $view->vars['choices'] = $this->choices($form->getData(), $options);
        } else {
            /** @var string $choiceLabel */
            $choiceLabel = $options['choice_label'];
            $view->vars['choices'] = [];
            if (!is_null($form->getData())) {
                $object = $form->getData();

                if ($object instanceof IdentifiableInterface && !is_null($object->getId())) {
                    $objectTitle = $this->getTitle($object, $choiceLabel);
                    $view->vars['choices'] = [new ChoiceView($object, $object->getId()->__toString(), $objectTitle)];
                }
            }
        }
    }

    public function getBlockPrefix(): string
    {
        return 'choice';
    }

    private function choices(mixed $collection, mixed $options): array
    {
        if (!$collection instanceof Collection || !is_array($options)) {
            return [];
        }

        return $collection
            ->map(function ($object) use ($options): ChoiceView {
                /** @var IdentifiableInterface $object */
                $objectTitle = $this->getTitle($object, $options['choice_label']);

                return new ChoiceView($object, (string) $object->getId(), $objectTitle);
            })
            ->toArray();
    }

    private function getTitle(IdentifiableInterface $object, ?string $choiceLabel): string
    {
        if (!is_null($choiceLabel)) {
            $methodName = 'get'.ucfirst($choiceLabel);
            if (method_exists($object, $methodName)) {
                return $object->{$methodName}();
            }
        }

        if (is_null($object->getId())) {
            return '';
        }

        return $object->getId()->__toString();
    }
}
