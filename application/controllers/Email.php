<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function send_mail()
    {
        $to = "depripahlakurnia@gmail.com";
        $subject = "Test Email";
        $text = "This is a test email sent using Mailjet.";
        $html = "<strong>This is a test email sent using Mailjet.</strong>";

        $response = $this->mailjet->send($to, $subject, $text, $html);

        if ($response) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
        }
    }
}
