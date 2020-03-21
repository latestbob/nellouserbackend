<?php

namespace App\Channels;

use DOMDocument;
use SimpleXMLElement;
use App\Notifications\BaseNotification;

class TextMessageChannel 
{

    private const BASE_URL = "http://sxmp.gw1.vanso.com";
    private const RES_PATH = "/api/sxmp/1.0";
    private const USERNAME = "NG.100.0419";
    private const PASSWORD = "K3rtHPG7";
    //private const ENCODING = "ISO-8859-1";
    private const ENCODING = "UTF-8";
    private const SOURCE_TYPE = "alphanumeric";
    private const SOURCE_ADDRESS = "Famacare";

    public function send($notifiable, BaseNotification $notification)
    {
        $message = $notification->toTextMessage($notifiable);

        //$this->sendSMS($message, [$notifiable->phone]);
        $phone = '+234' . substr(trim($notifiable->phone), 1);
        $this->processSMS($message, $phone);
    }

    private function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }


    private function processSMS($message, $phoneNumber)
    {
        // post SubmitRequest xml to server and wait for response
        $url = self::BASE_URL . self::RES_PATH;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:text/xml"));

        // create xml and set as content
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $this->createSubmitRequestXML($message, $phoneNumber));

        $result = curl_exec($ch);
        curl_close($ch);

        // handle SubmitResponse xml
        if ($result == null) {
            echo 'ERROR : No Response available';
            print_r(['msg' => "ERROR : No Response available"]);
        } else {
            // create xml from result
            $response = new SimpleXMLElement($result); // select root element
            if (isset($response->submitResponse[0])) {
                $root = $response->submitResponse[0];
            } else {
                $root = $response;
            }
            if ($root->error[0]['code'] == 0) {
                echo 'Successful';
                // successful submit if errce code is 0
                //$ticketId = $root->ticketId[0];
                /* * IMPORTANT NOTE : The TicketID value should be stored by your * application in your own database * since this value is

                important for support, troubleshooting, * and is used by

                callback/postback (DLR) operations. */
                //echo 'Successful';
                //return ['msg' => "Received TicketID : " . $ticketId];
            } else {
                echo 'Error: => ' . 
                // error if code is not 0 an error occured
                $message = $root->error[0]['message']; /*c * IMPORTANT NOTE : An an error occured and should be handled        here. */
                echo 'Error: ' . $message;
                //return ['msg' => "Error occured : " . $message];
            }
        }

    }

    
    /**  Returns the xml-string for an Submit Request 
     *  @param string $username 
     *  @param string $password  
     *  @param string $source_type  
     *  @param string $source_address 
     *  @param string $dest_address  
     *  @param string $text 
     * @param string $encoding
     * @param string $dlr * 
     * @return string */

    private function createSubmitRequestXML($message, $phoneNumber, $dlr = "false")
    {
        $text = $this->strToHex($message); 

        $xmldoc = new DOMDocument('1.0');
        $xmldoc->formatOutput = true;
        $root = $xmldoc->createElement('operation');
        $root = $xmldoc->appendChild($root);
        $root->setAttribute('type', 'submit');
        $account = $xmldoc->createElement('account');
        $account = $root->appendChild($account);
        $account->setAttribute('username', self::USERNAME);
        $account->setAttribute('password', self::PASSWORD);
        $submitRequest = $xmldoc->createElement('submitRequest');
        $submitRequest = $root->appendChild($submitRequest);
        $deliveryReport = $xmldoc->createElement('deliveryReport', $dlr);
        $deliveryReport = $submitRequest->appendChild($deliveryReport);
        $sourceAddress = $xmldoc->createElement('sourceAddress', self::SOURCE_ADDRESS);
        $sourceAddress = $submitRequest->appendChild($sourceAddress);
        $sourceAddress->setAttribute('type', self::SOURCE_TYPE);
        $destinationAddress = $xmldoc->createElement('destinationAddress', $phoneNumber);
        $destinationAddress = $submitRequest->appendChild($destinationAddress); // destination address type international is mandatory
        $destinationAddress->setAttribute('type', 'international');
        $msg = $xmldoc->createElement('text', $text);
        $msg = $submitRequest->appendChild($msg);
        $msg->setAttribute('encoding', self::ENCODING);
        return $xmldoc->saveXML();
    }


}