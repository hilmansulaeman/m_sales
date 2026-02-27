<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sendmail library
 */
 
class Sendmail
{
	var $CI = NULL;
	
	function __construct()
	{
		// get CI's object
		$this->CI =& get_instance();
	}
	
	//function send mail without attachment
    function send1($from,$to,$subject,$message)
    {
        $this->CI->load->library('email');
        $this->CI->config->load('setting');
        $config['mailtype'] = "html";
        $this->CI->email->initialize($config);
        $this->CI->email->from($this->CI->config->item('account_id'), $from);
        $this->CI->email->to($to);
		//$this->CI->email->cc($this->CI->config->item('email_cc2'));
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        $this->CI->email->send();
    }
	
	//function send mail without attachment
    function send2($from,$to,$subject,$message,$attachments=array())
    {
		$this->CI->load->library('email');
        $this->CI->config->load('setting');
        $config['mailtype'] = "html";
        $this->CI->email->initialize($config);
        $this->CI->email->from($this->CI->config->item('account_id'), $from);
        $this->CI->email->to($to);
		$this->CI->email->cc($this->CI->config->item('email_cc2'));
        $this->CI->email->subject($subject);
		foreach($attachments as $attachment){
		    $this->CI->email->attach($attachment);
		}
        $this->CI->email->message($message);
        $this->CI->email->send();
		$this->CI->email->clear(TRUE);
    }

    function send3($from = NULL, $to, $subject, $message, $cc = FALSE, $attachments = []) {
        $from = $from != NULL ? $from : 'PT Danamas Insan Kreasi Andalan';
        //$to   = "ahmad.zaki@ptdika.com";
        $this->CI->load->library('email');
        $this->CI->config->load('setting');
        $config['mailtype'] = "html";
        $this->CI->email->initialize($config);
        $this->CI->email->from($this->CI->config->item('email_hrd'), $from);
        $this->CI->email->to($to);
        // if ($cc)
        // $this->CI->email->cc($this->CI->config->item('email_cc2'));
        $this->CI->email->cc('info.hrd@ptdika.com');
        $this->CI->email->subject($subject);
		foreach ($attachments as $attachment) {
		    $this->CI->email->attach($attachment);
		}
        $this->CI->email->message($message);
        if ($this->CI->email->send()) {
        //   echo "Berhasil";
        } else {
          show_error($this->CI->email->print_debugger());
        }
    }
}