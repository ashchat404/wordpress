<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment
 *
 * @author Grzegorz
 */
class Wpjb_Module_AjaxNopriv_Payment 
{
    public function acceptAction()
    {
        $request = Daq_Request::getInstance();
        $engine = $request->getParam("engine");
        
        $class = Wpjb_Project::getInstance()->payment->getEngine($engine);
        
        $payment = new $class();
        $payment->bind($request->post(), $request->get());
        
        $object = $payment->getObject();
        
        /* @var $payment Wpjb_Payment_Interface */
        
        try {
            
            $result = $payment->processTransaction();
            
            $object->payment_paid = $result["paid"];
            $object->external_id = $result["external_id"];
            $object->is_valid = 1;
            $object->paid_at = date("Y-m-d H:i:s");
            $object->message = "";
            $object->save();
            
            $object->accepted();
            
            $mail = Wpjb_Utility_Message::load("notify_admin_payment_received");
            $mail->setTo(get_option("admin_email"));
            $mail->assign("payment", $object);
            $mail->send();
            
        } catch(Exception $e) {

            if($object->id>0) {
                $object->is_valid = -1;
                $object->message = $e->getMessage();
                $object->save();
            }
            
            $result = array(
                "result" => "fail",
                "message" => $e->getMessage()
            );
            
        }
        
        if($request->getParam("echo") == "1") {
            echo json_encode($result);
        }
        
        die;
    }
}

?>
