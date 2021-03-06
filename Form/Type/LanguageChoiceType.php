<?php

/*
 * Copyright 2015 Ralf Geschke <ralf@kuerbis.org>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Geschke\Bundle\Admin\TranslatorGUIBundle\Form\Type;

use Geschke\Bundle\Admin\TranslatorGUIBundle\Util\LocaleDefinitions;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Formular class to build a locale string choser
 * 
 */
class LanguageChoiceType extends AbstractType
{

    private $container;
    public $translator;
    public $submitButton;

    /**
     * LanguageChoiceType constructor
     * 
     * @param ContainerInterface $container
     * @param TranslatorInterface $translator
     * @param bool $submitButton
     */
    public function __construct(ContainerInterface $container, TranslatorInterface $translator, $submitButton = true)
    {
        $this->container = $container;
        $this->translator = $translator;
        $this->submitButton = $submitButton;
    }

    /**
     * Generate the language chooser formular
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $localesSmall = LocaleDefinitions::$csp_l10n_login_label;
        $localesFull = LocaleDefinitions::$csp_l10n_sys_locales;

        $assets = $this->container->get('templating.helper.assets');

        $baseUrl = $assets->getUrl('bundles/geschkeadmintranslatorgui/images/flags/');

        foreach ($localesSmall as $localeChoice => $language) {
            $choices[$localeChoice] = '<img src="' . $baseUrl . $localeChoice . '.gif" alt="locale: ' . $localeChoice . '" /> ' . $localeChoice;
        }

        foreach ($localesFull as $localeChoice => $localeData) {
            $choices[$localeChoice] = '<img src="' . $baseUrl . $localeData['country-www'] . '.gif" alt="locale: ' . $localeChoice . '" /> ' . $localeChoice . ' ' . $localeData['lang-native'];
        }

        $choices['misc'] = $this->translator->trans('form.languagechoice_label_misc'); 

        $that = $this;
        //asort($choices);
        $builder->add('bundle', 'hidden')
                ->add('locale', 'choice', array(
                    'label' => $this->translator->trans('form.languagechoice_label_choose_language'),
                    'required' => true,
                    'expanded' => true,
                    'choices' => $choices,
                    'empty_data' => null
                ))
                ->add('locale_additional', 'text', array('label' => $this->translator->trans('form.languagechoice_label_additional'))
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($that) {
            $form = $event->getForm();

            if ($that->submitButton) {
                $form->add('save', 'submit', array('label' => $that->translator->trans('form.languagechoice_label_submit'))); 
            }
        });
    }

    /**
     * Set data_class default value
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geschke\Bundle\Admin\TranslatorGUIBundle\Entity\LanguageFile',
        ));
    }

    /**
     * Get formular name 
     * 
     * @return string
     */
    public function getName()
    {
        return 'languagechoice';
    }

}
