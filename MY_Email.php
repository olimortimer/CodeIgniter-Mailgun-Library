<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Mailgun\Mailgun;

class MY_Email extends CI_Email
{

    // Replace the below with your Mailgun key and domain
    var $_mailgun_key    = 'key-XXX';
    var $_mailgun_domain = 'XXX.mailgun.org';

    var $_to             = '';
    var $_reply_to       = '';
    var $_cc             = '';
    var $_bcc            = '';
    var $_from           = '';
    var $_subject        = '';
    var $_message        = '';
    var $_attachments    = array();
    var $_mailtype       = 'html';

    public function initialize($config) {

        // Set our mailtype
        if(isset($config['mailtype']) && $config['mailtype'] == 'text') $this->_mailtype = 'text';

        return $this;
    }

    public function to($to, $name = '')
    {
        $to   = $this->_str_to_array($to);
        $to   = $this->clean_email($to);

        $name = $this->_str_to_array($name);

        $this->_to = $this->_format_emails_names($to, $name);

        return $this;
    }

    public function reply_to($replyto, $name = '')
    {
        $this->_reply_to = $name.' <'.$replyto.'>';
        return $this;
    }

    public function cc($cc, $name = '')
    {
        $cc   = $this->_str_to_array($cc);
        $cc   = $this->clean_email($cc);

        $name = $this->_str_to_array($name);

        $this->_cc = $this->_format_emails_names($cc, $name);

        return $this;
    }

    public function bcc($bcc, $name = '')
    {
        $bcc   = $this->_str_to_array($bcc);
        $bcc   = $this->clean_email($bcc);

        $name = $this->_str_to_array($name);

        $this->_bcc = $this->_format_emails_names($bcc, $name);

        return $this;
    }

    public function from($from, $name = '')
    {
        $this->_from = $name.' <'.$from.'>';
        return $this;
    }

    public function subject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    public function message($message)
    {
        $this->_message = $message;
        return $this;
    }

    public function attachments($attachments)
    {
        $this->_attachments[] = $attachments;
        return $this;
    }

    public function attach($attachment)
    {
        return $this->attachments($attachment);
    }

    public function send()
    {
        $mailgun = new Mailgun($this->_mailgun_key);

        $data = array(
            'from'           => $this->_from,
            'to'             => $this->_to,
            'subject'        => $this->_subject,
            $this->_mailtype => $this->_message
        );

        if($this->_reply_to) {
            $data['h:Reply-To'] = $this->_reply_to;
        }

        if($this->_cc) {
            $data['cc'] = $this->_cc;
        }

        if($this->_bcc) {
            $data['bcc'] = $this->_bcc;
        }

        for($i = 0; $i < count($this->_attachments); $i++) {
            $data['attachment[' . ($i+1) . ']'] = '@' . $this->_attachments[$i];
        }

        $result = $mailgun->sendMessage($this->_mailgun_domain, $data);

        return true;
    }

    protected function _format_emails_names($emails, $names = false)
    {
        foreach($emails as $k => $email) {
            $data[$k] = '';
            if(isset($names[$k]) && !empty($names[$k])) {
                $data[$k] .= $names[$k].' ';
            }
            $data[$k] .= '<'.$email.'>';
        }

        return implode(', ', $data);
    }
}
