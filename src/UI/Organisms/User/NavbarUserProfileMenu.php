<?php

namespace AppoloDev\SFToolboxBundle\UI\Organisms\User;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('navbar_user_profile_menu', template: '@SFToolbox/ui/organisms/user/navbar_user_profile_menu.html.twig')]
class NavbarUserProfileMenu
{
    public string $routePrefix = '';

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('routePrefix', 'front');
        $resolver->setAllowedTypes('routePrefix', ['string']);

        return $resolver->resolve($data);
    }
}
