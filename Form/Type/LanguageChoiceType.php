<?php
/**
 * Created by PhpStorm.
 * User: geschke
 * Date: 12.01.2015
 * Time: 11:39
 */

namespace Geschke\Bundle\Admin\TranslatorGUIBundle\Form\Type;

use Geschke\Bundle\Admin\TranslatorGUIBundle\Util\LocaleDefinitions;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class LanguageChoiceType extends AbstractType
{

    private $container;
    private $translator;

    public function __construct(ContainerInterface $container, TranslatorInterface $translator)
    {
        $this->container = $container;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $localesSmall = LocaleDefinitions::$csp_l10n_login_label;
        $localesFull = LocaleDefinitions::$csp_l10n_sys_locales;

        $assets = $this->container->get('templating.helper.assets');

        $baseUrl = $assets->getUrl('bundles/geschkeadmintranslatorgui/images/flags/');

        foreach ($localesSmall as $localeChoice => $language ) {
            $choices[$localeChoice] = '<img src="' . $baseUrl . $localeChoice . '.gif" alt="locale: ' . $localeChoice . '" /> ' . $localeChoice ;
        }

        foreach ($localesFull as $localeChoice => $localeData ) {
            $choices[$localeChoice] = '<img src="' . $baseUrl . $localeData['country-www']. '.gif" alt="locale: ' . $localeChoice . '" /> ' . $localeChoice . ' ' . $localeData['lang-native'] ;
        }

        $choices['misc'] = "Miscellaneous, please specify below:";



        //asort($choices);
        $builder->add('bundle', 'hidden')
            ->add('locale', 'choice', array(
                'label'     => $this->translator->trans("Choose language"),
                'required'  => true,
                'expanded' => true,
                'choices' => $choices,
                'empty_data' => null
            ))->add('locale_additional', 'text', array('label' => $this->translator->trans("or another locale definition")))
            //  ->add('dueDate', 'date')
            ->add('save', 'submit', array('label' => $this->translator->trans("Create new language file")));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geschke\Bundle\Admin\TranslatorGUIBundle\Entity\LanguageFile',
        ));
    }

    public function getName()
    {
        return 'languagechoice';
    }
}