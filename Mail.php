<?php

class MyFw_Mail
    extends Zend_Mail
{
    /**
     * the zend Config object
     * @var zend_config
     */
    private $_config;

    /**
     *
     * @var Zend_View
     */
    static $_defaultView;
    /**
     * current instance of our Zend_View
     * @var Zend_View
     */
    protected $_view;

    /**
     * set up the mail object
     */
    public function  __construct($charset = "UTF-8")
    {
        parent::__construct($charset);

        // set Transport
		$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/resources/Config/email.ini', APPLICATION_ENV);
        if ($this->_config->use_smtp_mail == 1) {
			Zend_Mail::setDefaultTransport( new Zend_Mail_Transport_Smtp($this->_config->smtp_host, $this->_config->smtp_cfg->toArray()) );
        }
		
		// Set Default From
        $this->SetFrom($this->_config->default_email, $this->_config->default_name);

        // set Zend_View
        $this->_view = self::getDefaultView();
    }

    protected static function getDefaultView()
    {
        if(self::$_defaultView === null) {
            self::$_defaultView = new MyFw_View();
        }
        return self::$_defaultView;
    }
    
    // set params in View instance
    public function setViewParam($property, $value)
    {
        $this->_view->assign($property, $value);
        return $this;
    }

    public function sendHtmlTemplate($template, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE)
    {
        $html = $this->_view->fetch($template);
        $this->setBodyHtml($html);
        $this->setBodyText(strip_tags($html));
        try {
            if(APPLICATION_ENV == "production") {
                return $this->send();
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
           /*
            * TODO: Catch Error and do something....
            echo "<pre>";
            echo $exc->getMessage();
            echo "</pre>";die;
            */
        }
    }

    
    
    
}