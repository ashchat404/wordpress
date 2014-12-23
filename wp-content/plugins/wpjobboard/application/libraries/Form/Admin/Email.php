<?php
/**
 * Description of Email
 *
 * @author greg
 * @package 
 */

class Wpjb_Form_Admin_Email extends Daq_Form_ObjectAbstract
{
    protected $_model = "Wpjb_Model_Email";

    public function __construct($id = null)
    {
        parent::__construct($id);
        if($id<1) {
            throw new Exception("Email template identified as $id does not exist.");
        }
    }

    public function init()
    {
        $e = $this->create("id", "hidden");
        $e->setRequired(true);
        $e->setValue($this->_object->id);
        $e->addFilter(new Daq_Filter_Int());
        $e->addValidator(new Daq_Validate_Db_RecordExists($this->_model, "id"));
        $this->addElement($e);

        $e = $this->create("mail_from_name");
        $e->setRequired(true);
        $e->setValue($this->_object->mail_from_name);
        $e->setLabel(__("Sender Name", "wpjobboard"));
        $this->addElement($e);

        $e = $this->create("mail_from");
        $e->setRequired(true);
        $e->setValue($this->_object->mail_from);
        $e->setLabel(__("Sender Email", "wpjobboard"));
        $e->addValidator(new Daq_Validate_Email());
        $this->addElement($e);
        
        $e = $this->create("mail_bcc");
        $e->setValue($this->_object->mail_bcc);
        $e->setLabel(__("BCC", "wpjobboard"));
        $e->setHint(__("List email address to which this email should be sent as hidden copy, seperate emails with comma.", "wpjobboard"));
        $this->addElement($e);

        $e = $this->create("is_active", "checkbox");
        $e->setValue($this->_object->is_active);
        $e->setLabel(__("Activity", "wpjobboard"));
        $e->addFilter(new Daq_Filter_Int);
        $e->addOption(1, 1, __("Enable this email notification.", "wpjobboard"));
        $this->addElement($e);
        
        $e = $this->create("mail_title");
        $e->setRequired(true);
        $e->setValue($this->_object->mail_title);
        $e->setLabel(__("Email Title", "wpjobboard"));
        $e->addValidator(new Daq_Validate_StringLength(1, 120));
        $this->addElement($e);
        
        $e = $this->create("format", "select");
        $e->addClass("wpjb-mail-body-select");
        $e->setRequired(true);
        $e->setValue($this->_object->format);
        $e->setLabel(__("Email Format", "wpjobboard"));
        $e->addOption("text/plain", "text/plain", __("Plain Text", "wpjobboard"));
        $e->addOption("text/html", "text/html", __("HTML", "wpjobboard"));
        $this->addElement($e);
        
        $e = $this->create("mail_body_html", "textarea");
        $e->addClass("wpjb-mail-body");
        $e->setValue($this->_object->mail_body_html);
        $e->setLabel(__("Email Body", "wpjobboard"));
        $e->setEditor(Daq_Form_Element_Textarea::EDITOR_FULL);
        $this->addElement($e);
        
        $e = $this->create("mail_body_text", "textarea");
        $e->addClass("wpjb-mail-body");
        $e->setValue($this->_object->mail_body_text);
        $e->setLabel(__("Email Body", "wpjobboard"));

        $this->addElement($e);

        apply_filters("wpja_form_init_email", $this);
    }
    
    public function isValid(array $values) 
    {
        if($values["format"] == "text/plain") {
            $this->getElement("mail_body_text")->setRequired(true);
            $this->getElement("mail_body_text")->addValidator(new Daq_Tpl_Validate());
        } else {
            $this->getElement("mail_body_html")->setRequired(true);
            $this->getElement("mail_body_html")->addValidator(new Daq_Tpl_Validate());
        }
        
        
        return parent::isValid($values);
    }
    
    public function save($append = array()) 
    {
        parent::save($append);
        
        apply_filters("wpja_form_save_email", $this);
    }
}


?>