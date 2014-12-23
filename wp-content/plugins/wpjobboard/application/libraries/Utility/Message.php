<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Message
 *
 * @author greg
 */
class Wpjb_Utility_Message
{
    protected $_header = array();

    protected $_title = null;

    protected $_body = null;

    protected $_file = array();

    protected $_to = null;

    protected $_param = array();
    
    protected $_tpl = null;

    /**
     * Mail template
     *
     * @var Wpjb_Model_Mail
     */
    protected $_template = null;

    public function __construct($message)
    {
        $this->_template = $message;
        $this->setTitle($this->_template->mail_title);
        $this->setBodyText($this->_template->mail_body_text);
        $this->setBodyHtml($this->_template->mail_body_html);
        $this->setFrom($this->_template->mail_from);
        $this->setTo($this->_template->mail_from);
        
        if($this->_template->mail_bcc) {
            $this->addHeader("Bcc", $this->_template->mail_bcc);
        }
        
        $this->_tpl = new Daq_Tpl_Email;
    }
    
    public function assign($var, $value)
    {
        if($value instanceof Daq_Db_OrmAbstract) {
            $value = $value->toArray();
        }
        
        $this->_tpl->assign($var, $value);
    }

    public function addHeader($key, $value)
    {
        $this->_header[$key] = $value;
    }

    public function getHeaders()
    {
        return $this->_header;
    }

    public function addFiles($files) 
    {
        if(!is_array($files)) {
            $files = (array)$files;
        }
        
        $this->_file = $files;
    }
    
    public function getFiles()
    {
        return $this->_file;
    }

    public function setFrom($email, $name = null)
    {
        if($name == null) {
            $name = $email;
        }

        $this->addHeader("From", "$name <$email>");
    }

    public function setTo($email)
    {
        $this->_to = $email;
    }

    public function getTo()
    {
        return $this->_to;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getBody()
    {
        return $this->getBodyText();
    }

    public function setBody($body)
    {
        $this->setBodyText($body);
    }

    public function getBodyText()
    {
        return $this->_body_text;
    }

    public function setBodyText($body)
    {
        $this->_body_text = $body;
    }
    
    public function getBodyHtml()
    {
        return $this->_body_html;
    }

    public function setBodyHtml($body)
    {
        $this->_body_html = $body;
    }
    
    public function getTemplate()
    {
        return $this->_template;
    }

    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    protected function _parse($text, $param)
    {
        return $this->_tpl->draw($text);
    }

    protected function _nl2br($string)
    {
        $lines = preg_split('/\r\n|\n|\r/', $string);
        $output = "";
        foreach((array)$lines as $line) {
            $line = rtrim($line);
            if($line && $line[strlen($line)-1] == "}") {
                // string ends with '}'
                preg_match('/\{[^\}]+\}/', $line, $matches);
                $index = count($matches)-1;
                if($index>=0 && substr($matches[$index], 0,2)=='{$')  {
                    $output .= $line."<br />";
                } else {
                    $output .= $line;
                }
            } else {
                $output .= $line."<br />";
            }
        }
        return $output;
    }
    
    protected function _br2nl($string)
    {
      return eregi_replace('<br[[:space:]]*/?'.'[[:space:]]*>',chr(13).chr(10),$string);
    } 
    
    public function send()
    {
        $is_active = $this->_template->is_active;

        if($this->_template->format == "text/plain") {
            $body = $this->getBodyText();
            $body = $this->_nl2br($body);
            $message = $this->_tpl->draw($body);
            $message = $this->_br2nl($message);
            $message = ltrim($message);
        } else {
            $body = $this->getBodyHtml();
            $body = $this->_nl2br($body);
            $message = $this->_tpl->draw($body);
            $this->addHeader("Content-type", "text/html");
        }
        
        $to = $this->getTo();
        $subject = $this->_parse($this->getTitle(), $this->_param);
        $header = $this->getHeaders();
        $attachments = $this->getFiles();
        $headers = array();
        foreach($header as $t=>$x) {
            $headers[] = "$t: $x";
        }

        extract(apply_filters("wpjb_message", array(
            "key" => $this->_template,
            "is_active" => $is_active,
            "to" => $to,
            "subject" => $subject,
            "message" => $message,
            "headers" => $headers,
            "attachments" => $attachments
        ), $this));
        
        if($is_active) {
            wp_mail($to, $subject, $message, $headers, $attachments);
        }
    }
    
    /**
     * Loads model
     *
     * @param name $key
     * @return Wpjb_Utility_Message
     * @throws Exception 
     */
    public static function load($key)
    {
        $query = new Daq_Db_Query;
        $query->select();
        $query->from("Wpjb_Model_Email t");
        $query->where("name = ?", $key);
        $query->limit(1);
        
        $list = $query->execute();
        
        if(empty($list)) {
            throw new Exception("Email template [$key] does not exist.");
        } else {
            return new self($list[0]);
        }
    }
}
?>
