<?php
/**
 * ELangUrlManager.php
 *
 * Create seo-friendly language urls
 *
 * Adds the language as first the part to a created url
 * Sets the language on parseUrl
 *
 * PHP version 5.2+
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2013 myticket it-solutions gmbh
 * @license NEW BSD license
 * @version 1.0
 */
class ELangUrlManager extends CUrlManager
{
    /**
     * The language GET param
     * @var string
     */
    public $languageParam = 'lang';

    /**
     * The languages as assoziative array(language=>label)
     * The language url feature is deactivated if no languages are assigned
     */
    public $languages = array();

    /**
     * Set > 0 to set a current language cookie
     */
    public $cookieDays = 0;

    /**
     * Internal used
     * @var bool
     */
    protected $_languagesRuleAdded = false;

    /**
     * Initializes the application component.
     * Set the default language and add the rules
     */
    public function init()
    {
        $this->setDefaultLanguage();
        $this->addLanguageUrlRules();
        parent::init();
    }

    /**
     * Check if languages are assigned
     *
     * @return bool
     */
    public function languagesEnabled()
    {
        return !empty($this->languages);
    }

    /**
     * The browsers ACCEPT_LANGUAGE is a list of weighted values
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
     *
     * Parse the list of accepted languages and available languages and find the best match
     * I don't use Yii::app()->getRequest()->getPreferredLanguage() because of de != de_de ..??
     *
     * @return string
     */
    public function getPreferredLanguage()
    {
        if(empty($this->languages))
            return Yii::app()->language;

        reset($this->languages);
        $firstConfigured = key($this->languages);

        $accepted = $this->parseLanguageList($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        if(empty($accepted))
            return $firstConfigured;

        $available =  array('1.0' => array_keys($this->languages));
        $matches = $this->findMatches($accepted, $available);

        if(empty($matches))
            return $firstConfigured;

        foreach($matches as $quality => $languages)
            if(isset($languages[0]))
                return $languages[0];

        return $firstConfigured;
    }


    /**
     * Return the name/label of the current language
     *
     * @return string
     */
    public function getLanguageName($language=null)
    {
        if($language===null)
           $language = Yii::app()->getLanguage();
        return isset($this->languages[$language]) ? $this->languages[$language] : $language;
    }

    /**
     * Set from cookie if set,
     * otherwise detect the preferred browser language
     *
     * @return bool
     */
    public function setDefaultLanguage()
    {
        if(!$this->languagesEnabled())
            return false;

        if (($language = $this->getCookie()) !== null)
            $this->setLanguage($language);
        else
            $this->setLanguage($this->getPreferredLanguage());
    }


    /**
     * For seofriendly url routes
     * Adds the rules
     * '<languageParam:(lang1|lang2|...)>'=>'/'
     * '<languageParam:(lang1|lang2|...)>/'=>'/'
     * '<languageParam:(lang1|lang2|...)>/<route:[\w\/]+>'=>'<route>'
     *
     * Called in method init()
     *
     */
    public function addLanguageUrlRules()
    {
        if (!$this->_languagesRuleAdded && $this->languagesEnabled())
        {
            $languages = $this->languages;
            $curLanguage = Yii::app()->language;
            if (!isset($languages[$curLanguage]))
                $languages[$curLanguage] = $curLanguage;

            $rule = '<' . $this->languageParam . ':(';
            foreach ($languages as $language=>$label)
                $rule .= $language . '|';

            $langRoute = substr($rule, 0, -1) . ')>';

            $rules[$langRoute] = '/';
            $rules[$langRoute . '/'] = '/';
            $rules[$langRoute . '/<route:[\w\/]+>/*'] = '<route>';

            $this->addRules($rules, false);
            $this->_languagesRuleAdded = true;
        }
    }


    /**
     * Create the url of the current controller/action with all GET params
     *
     * @param $language
     * @param array $params
     * @param string $ampersand
     * @return string
     */
    public function createControllerLanguageUrl($language, $params = array(), $ampersand = '&')
    {
        if (isset($this->languages[$language]))
        {
            $params[$this->languageParam] = $language;
        }

        $params = array_merge($_GET, $params); //add the GET params

        return Yii::app()->controller->createUrl('', $params, $ampersand);
    }

    /**
     * Get the current controller/action with all configured languages prefix
     * Used to implement a language switch
     *
     * @param array $params
     * @param string $ampersand
     * @return array
     */
    public function getLanguageUrls($params = array(), $ampersand = '&')
    {
        $urls = array();

        foreach ($this->languages as $language=>$label)
            $urls[$language] = $this->createControllerLanguageUrl($language, $params, $ampersand);

        return $urls;
    }


    /**
     * Get the links for switching the language in the view of the current controller/action
     *
     * @param array $htmlOptions
     * @param array $params
     * @param string $ampersand
     * @param array $labels array(language=>label,...); the configured languages if empty
     * @return array
     */
    public function getLanguageLinks($htmlOptions = array(),$params = array(), $ampersand = '&', $labels = array())
    {
        $links = array();
        $labels = array_merge($this->languages,$labels);

        $urls = $this->getLanguageUrls($params, $ampersand);
        foreach ($urls as $language => $url)
            $links[] = CHtml::link($labels[$language], $url, $htmlOptions);

        return $links;
    }

    /**
     * Get the links with images as labels for switching the language in the view of the current controller/action
     *
     *
     * @param array $images array(language=>imageSource,...)
     * @param bool $useBaseUrl wether to add the Yii::app()->baseUrl
     * @param array $imageHtmlOptions
     * @param array $linkHtmlOptions
     * @param array $params
     * @param string $ampersand
     * @return array
     */
    public function getLanguageImageLinks($images = array(), $useBaseUrl=true, $imageHtmlOptions = array(), $linkHtmlOptions = array(), $params = array(), $ampersand = '&')
    {
        $labels = array();

        $app = Yii::app();
        foreach($images as $language => $src)
        {
            if($useBaseUrl)
               $src = $app->baseUrl .'/' .$src;
            $labels[$language] = CHtml::image($src,'',$imageHtmlOptions);
        }

        return $this->getLanguageLinks($linkHtmlOptions,$params,$ampersand,$labels);
    }


    /**
     * Get the language as menuitems
     * The top item is the current language
     * more languages as subitems
     *
     * @param array $itemOptions
     * @param array $params
     * @param string $ampersand
     * @return array
     */
    public function getLanguageMenuItems($itemOptions=array(),$params = array(), $ampersand = '&')
    {
        $currentLanguage = Yii::app()->getLanguage();
        $urls = $this->getLanguageUrls($params, $ampersand);
        $items = array();
        foreach ($urls as $language => $url)
          if($language != $currentLanguage)
            $items[] = array('label'=>$this->getLanguageName($language),'url'=>$url,'itemOptions'=>$itemOptions);

        return array(array('label'=>$this->getLanguageName(), 'url'=>'#', 'items'=>$items));
    }


    /**
     * Get a dropdownList to switch between languages
     *
     * @param array $labels the configured languages if empty
     * @param array $htmlOptions
     * @param array $params
     * @param string $ampersand
     * @return string
     */
    public function getLanguageDropDown($labels = array(),$htmlOptions = array('id'=>'language-switch'),$params = array(), $ampersand = '&')
    {
        $urls = $this->getLanguageUrls($params, $ampersand);
        if(empty($urls))
            return;

        $data = array();
        $options = array();
        $labels = array_merge($this->languages,$labels);


        foreach ($urls as $language => $url)
        {
            $data[$language]=$labels[$language];
            $options[$language]=array('langurl'=>$url);
        }

        $htmlOptions['onchange'] = "js:location.href=this.options[this.selectedIndex].getAttribute('langurl');";
        $htmlOptions['options'] = $options;

        return CHtml::dropDownList('', Yii::app()->getLanguage(), $data, $htmlOptions);
    }


    /**
     * Adds the current language as first part of the created url
     *
     * @param string $route
     * @param array $params
     * @param string $ampersand
     * @return string
     */
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if ($this->languagesEnabled() && !isset($params[$this->languageParam]) && !empty(Yii::app()->language))
            $params[$this->languageParam] = Yii::app()->getLanguage();

        return parent::createUrl($route, $params, $ampersand);
    }

    /**
     * Parses the user request and sets the language if $_GET[$this->languageParam] isset
     *
     * @param CHttpRequest $request the request application component
     * @return string the route (controllerID/actionID) and perhaps GET parameters in path format.
     */
    public function parseUrl($request)
    {
        $route = parent::parseUrl($request);
        $this->setLanguage();
        return $route;
    }

    public function setLanguage($language = null)
    {
        if(!$this->languagesEnabled())
            return false;

        if(empty($language))
        {
            if(isset($_GET[$this->languageParam]))
              $language = $_GET[$this->languageParam];
            else
              $language = $this->getCookie();
        }

        if (!empty($language) &&
            $language != Yii::app()->language &&
            array_key_exists($language, $this->languages)
        )
        {
            Yii::app()->setLanguage($language);
            $this->setCookie($language);
            return true;
        }

        return false;
    }



    /**
     * Set the language cookie if cookieDays>0
     *
     * @param $language
     */
    protected function setCookie($language)
    {
        if ($this->languagesEnabled() && !empty($this->cookieDays) && (int)$this->cookieDays > 0)
        {
            if($language != $this->getCookie())
            {
                $name = $this->getCookieName();
                $cookie = new CHttpCookie($name, $language);
                $cookie->expire = time() + 60 * 60 * 24 * (int)$this->cookieDays;
                Yii::app()->request->cookies[$name] = $cookie;
            }
        }
    }

    /**
     * Read the language cookie
     *
     * @return string
     */
    protected function getCookie()
    {
        $value = null;

        if ($this->languagesEnabled() && !empty($this->cookieDays) && (int)$this->cookieDays > 0)
        {
            $name = $this->getCookieName();
            if (!empty($this->cookieDays))
                $value = isset(Yii::app()->request->cookies[$name]) ? Yii::app()->request->cookies[$name]->value : null;
        }

        return $value;
    }

    /**
     * The name of the language cookie
     *
     * @return string
     */
    protected function getCookieName()
    {
        return get_class($this) . $this->languageParam;
    }

    /**
     * Parse list of comma separated language tags and sort it by the quality value
     *
     * Code from Gumbo
     * @link http://stackoverflow.com/a/3771447
     *
     * @param $languageList
     * @return array
     */
    protected function parseLanguageList($languageList)
    {
        if (is_null($languageList)) {
            if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                return array();
            }
            $languageList = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        $languages = array();
        $languageRanges = explode(',', trim($languageList));
        foreach ($languageRanges as $languageRange) {
            if (preg_match('/(\*|[a-zA-Z0-9]{1,8}(?:-[a-zA-Z0-9]{1,8})*)(?:\s*;\s*q\s*=\s*(0(?:\.\d{0,3})|1(?:\.0{0,3})))?/', trim($languageRange), $match)) {
                if (!isset($match[2])) {
                    $match[2] = '1.0';
                } else {
                    $match[2] = (string) floatval($match[2]);
                }
                if (!isset($languages[$match[2]])) {
                    $languages[$match[2]] = array();
                }
                $languages[$match[2]][] = strtolower($match[1]);
            }
        }
        krsort($languages);
        return $languages;
    }

    /**
     * Compare two parsed arrays of language tags and find the matches
     *
     * Code from Gumbo
     * @link http://stackoverflow.com/a/3771447
     *
     * @param $accepted
     * @param $available
     * @return array
     */
    protected  function findMatches($accepted, $available)
    {
        $matches = array();
        $any = false;
        foreach ($accepted as $acceptedQuality => $acceptedValues) {
            $acceptedQuality = floatval($acceptedQuality);
            if ($acceptedQuality === 0.0) continue;
            foreach ($available as $availableQuality => $availableValues) {
                $availableQuality = floatval($availableQuality);
                if ($availableQuality === 0.0) continue;
                foreach ($acceptedValues as $acceptedValue) {
                    if ($acceptedValue === '*') {
                        $any = true;
                    }
                    foreach ($availableValues as $availableValue) {
                        $matchingGrade = $this->matchLanguage($acceptedValue, $availableValue);
                        if ($matchingGrade > 0) {
                            $q = (string) ($acceptedQuality * $availableQuality * $matchingGrade);
                            if (!isset($matches[$q])) {
                                $matches[$q] = array();
                            }
                            if (!in_array($availableValue, $matches[$q])) {
                                $matches[$q][] = $availableValue;
                            }
                        }
                    }
                }
            }
        }
        if (count($matches) === 0 && $any) {
            $matches = $available;
        }
        krsort($matches);
        return $matches;
    }

    /**
     * Compare two language tags and distinguish the degree of matching
     *
     * Code from Gumbo
     * @link http://stackoverflow.com/a/3771447
     *
     * @param $a
     * @param $b
     * @return float|int
     */
    protected function matchLanguage($a, $b)
    {
        $a = explode('-', $a);
        $b = explode('-', $b);
        for ($i=0, $n=min(count($a), count($b)); $i<$n; $i++) {
            if ($a[$i] !== $b[$i]) break;
        }
        return $i === 0 ? 0 : (float) $i / count($a);
    }

}