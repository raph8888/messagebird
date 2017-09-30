<?php
namespace App;

use MessageBird\Client;
use MessageBird\Objects\Message;

/**
 * Service to send text messages using MessageBird
 */
class SMS
{
    private $client;
    private $originator;
    private $timestamp;

    public function __construct()
    {
        $this->timestamp = time();
    }

    /*
     * Sends the SMS
     */
    public function send($values)
    {
        // Validation
        $phone_number = $this->validatePhone($values["recipient"]);
        $message_body = $this->validateMessage($values["message"]);
        $this->validateOriginator($values["originator"]);

        $message_length = strlen($message_body);
        $long_sms = $message_length > 160;

        $response = $long_sms ?
            $this->concatenateSMS($phone_number, $message_body, $message_length) :
            $this->callMessageBird($phone_number, $message_body);

        return $response;
    }

    /*
     * Validates phone number
     * Only possible to send messages to The Netherlands
     *
     * @throws Exception()
     */
    function validatePhone($phone_number)
    {
        if (strlen($phone_number) < 10) {
            throw new \Exception('The number is incorrect, it must have at least 10 characters');
        }
        if (substr($phone_number, 0, 4) == '0031') {
            return '31' . substr($phone_number, 4);
        } elseif (substr($phone_number, 0, 3) == '316') {
            return $phone_number;
        } elseif (substr($phone_number, 0, 2) == '06') {
            return substr_replace($phone_number, '31', 0, 1);
        } elseif (substr($phone_number, 0, 4) == '+316') {
            return $phone_number;
        }
        throw new \Exception('Unexpected format of phone number, expected it starts with 0031, 31 or 316');
    }

    /*
     * Checks if message body is empty
     *
     * @param $message
     * @throws Exception()
     */
    function validateMessage($message)
    {
        if (empty($message)) {
            throw new \Exception('Empty messages are invalid');
        }
        return $message;
    }

    /*
     * Checks if the originator is empty
     *
     * @param $originator
     * @throws Exception()
     */
    function validateOriginator($originator)
    {
        if (empty($originator)) {
            throw new \Exception('Empty originator is invalid');
        }
        $this->originator = $originator;
    }

    /*
     * Function called when message has over 160 characters
     * Concatenates the SMS and sends multiple messages
     *
     * @param $phone_number
     * @param $message_body
     *
     * returns array of $responses
     */
    function concatenateSms($phone_number, $message_body, $message_length)
    {
        $response = array();

        // Find the amount of messages necessary to send the long text
        $total_message_parts = ceil($message_length / 153);

        // Set a variable that defines from where each message should start
        $character_index_start = 0;

        // Loop as many times as messages needed
        for ($i = 1; $i <= $total_message_parts; $i++) {

            // Get the first 153 characters starting from the begging.
            $message_part = substr($message_body, $character_index_start, 153);

            // Increase the value of the variable the defines where the message starts
            $character_index_start += 153;

            //callMessageBird
            $response[$i] = $this->callMessageBird($phone_number, $message_part);
        }

        return $response;
    }

    /*
     * Makes the connection with MessageBird
     *
     * @param $phone_number
     * @param $message
     */
    function callMessageBird($phone_number, $message)
    {
        $this->client = new Client('7pgSx0IlPkp4nVpkgAVGv8KLo');

        $Message = new Message();
        $Message->originator = $this->originator;
        $Message->recipients = $phone_number;
        $Message->body = $message;
        $response = $this->client->messages->create($Message);
        return $response;
    }
}