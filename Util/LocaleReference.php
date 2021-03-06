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

namespace Geschke\Bundle\Admin\TranslatorGUIBundle\Util;

use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

/**
 * This class handles the session of locale reference setting.
 *
 * @author geschke
 */
class LocaleReference
{

    private $request;
    private $locale;
    private $logger;

    /**
     * Constructor 
     * 
     * @param Request $request
     * @param LoggerInterface $logger
     */
    public function __construct(Request $request, LoggerInterface $logger)
    {

        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * Set the locale reference value
     * 
     * @param string $locale
     * @return bool
     */
    public function setLocaleReference($locale)
    {
        $this->logger->info(__METHOD__ . ' set locale to ' . $locale);

        $session = $this->request->getSession();
        $session->set('translator-gui-bundle-localeReference', $locale);
        return true;
    }

    /**
     * Get the current locale reference value from session, 
     * if not available, the default setting 'en' will be returned 
     *
     * @return string
     */
    public function getLocaleReference()
    {
        $session = $this->request->getSession();
        $localeReference = $session->get('translator-gui-bundle-localeReference');
        if (!$localeReference) {
            $localeReference = 'en';
        }
        $this->setLocaleReference($localeReference);
        $this->logger->info(__METHOD__ . 'get locale reference with value  ' . $localeReference);

        return $localeReference;
    }

}
