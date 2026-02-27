<?php

/*
 * @Link Untuk URL pada Frontend
 */
if(!function_exists("web_url"))
{
    function web_url($url=FALSE)
    {
        $site_url = base_url().link_index();
        if($url and $url != '/')
            $site_url .= $url;

        return $site_url;
    }
}

/*
 * @Menambahkan index.php jika htaccess tidak berjalan
 */
if(!function_exists("link_index"))
{
    function link_index()
    {
        if(xml('allow_htaccess'))
            return '';
        else   
            return 'index.php/';
    }
}

/*
 * @Age
 */
if(!function_exists("age"))
{
    function age($birthDate)
    {
        //explode the date to get month, day and year
        $birthDate = explode("-", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));

        return $age;
    }
}

/*
 * @Url Youtube
 */
if(!function_exists("parse_url_youtube"))
{
    function parse_url_youtube($url,$key)
    {
        //$url = 'http://www.youtube.com/watch?v=Z29MkJdMKqs&feature=grec_index';

        // break the URL into its components
        $parts = parse_url($url);

        // $parts['query'] contains the query string: 'v=Z29MkJdMKqs&feature=grec_index'

        // parse variables into key=>value array
        $query = array();
        parse_str($parts['query'], $query);

        //echo $query['v']; // Z29MkJdMKqs
        //echo $query['feature'] ;// grec_index

        return $query[$key];
    }
}

/*
 * @Date format UTC
 */
if(!function_exists("date_now"))
{
    function date_now($time=false)
    {
        date_default_timezone_set('UTC');
        if($time){
            $date = date('Y-m-d H:i:s');
            return date("Y-m-d H:i:s", strtotime('+7 hours', strtotime($date)));
        }else {
           return date('Y-m-d');
        }
    }
}

/*
 * @Format date
 */
if(!function_exists("format_date"))
{
    function format_date($date,$format = 'F d, Y')
    {
        $return = '';
        if(!empty($date)){
            $date = new DateTime($date);
            $return .=$date->format($format);
        }
        return $return;
    }
}

/*
 * @Format date Indonesia
 */
if(!function_exists("format_date_ID"))
{
    function format_date_ID($date = null)
    {
        $curentdate = date('Y',time()) ."-". date('m',time())."-". date('d',time());
        $date = empty($date) ? $curentdate : $date;

        $date = new DateTime($date);

        $day = $date->format("j");
        $month = $date->format("n");
        $year = $date->format("Y");

        $days = date("w",mktime(0,0,0,$month,$day,$year));

        $out = DayID($days).', ';
        $out .= $day.' ';
        $out .= MonthID($month).' ';
        $out .= $year.' ';

        return $out;
    }
	
    function DayID($day = 0)
    {
        $strDay = "";
        switch($day){
                case 0:$strDay = "Minggu";break;
                case 1:$strDay = "Senin";break;
                case 2:$strDay = "Selasa";break;
                case 3:$strDay = "Rabu";break;
                case 4:$strDay = "Kamis";break;
                case 5:$strDay = "Jumat";break;
                case 6:$strDay = "Sabtu";break;
        };

        return $strDay;
    }

    function MonthID($m = 0)
    {
        $strMonth = "";
        switch($m){
                case 1:$strMonth = "Januari";break;
                case 2:$strMonth = "Februari";break;
                case 3:$strMonth = "Maret";break;
                case 4:$strMonth = "April";break;
                case 5:$strMonth = "Mei";break;
                case 6:$strMonth = "Juni";break;
                case 7:$strMonth = "Juli";break;
                case 8:$strMonth = "Agustus";break;
                case 9:$strMonth = "September";break;
                case 10:$strMonth = "Oktober";break;
                case 11:$strMonth = "November";break;
                case 12:$strMonth = "Desember";break;
        };

        return $strMonth;
    }
}


/*
 * @Config Setting
 */
if(!function_exists("xml"))
{
    function xml($id = '')
    {
    	$CI =& get_instance();

        return $CI->config->item($id);
    }
}

/*
 * @function untuk base asset url 
 */
if(!function_exists("asset_url"))
{
    function asset_url()
    {
        return base_url() . 'assets/';
    }
}

/*
 * @function untuk base cleint url 
 */
if(!function_exists("client_url"))
{
    function client_url()
    {
        return base_url() . 'clients/';
    }
}


/*
 * =============================================================================
 * BEGIN FUNCTION CUSTOM
 * =============================================================================
 */

/*
 * @function untuk menampilkan data currency
 */
if(!function_exists("currency"))
{
    function currency()
    {
        $CI =& get_instance();
        $CI->load->model('currency_mod');
        
        return $CI->currency_mod->get_rows();
    }
}

if(!function_exists("get_currency"))
{
    function get_currency()
    {
        $CI =& get_instance();
        $CI->load->model('currency_mod');
        
        $row = $CI->currency_mod->get(currency_id());
        if(!$row){
            $row = $CI->currency_mod->get_first_row();
        }
        
        return $row;
    }
}

if(!function_exists("currency_id"))
{
    function currency_id()
    {
        $CI =& get_instance();
        $currency_id = $CI->session->userdata('currency_id');
        
        if(isset($currency_id)){
            if($currency_id > 0)
                return $currency_id;
            else
                return 1;
        }else{
            return 1;
        }
    }
}

if(!function_exists("is_membership"))
{
    function is_membership()
    {
        $CI =& get_instance();
        $is_logged_in = $CI->session->userdata('is_logged_in');
        
        return $v = isset($is_logged_in) ? $is_logged_in : false;
    }
}

/*
 * @function untuk menampilkan data user di session
 */
if(!function_exists("user_id"))
{
    function user_id()
    {
        $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id');

        return $v = isset($user_id) ? $user_id : 0;
    }
}

if(!function_exists('name'))
{
    function name()
    {
        $CI =& get_instance();
        $name = $CI->session->userdata('name');

        return $v = isset($name) ? $name : "";
    }
}

if(!function_exists('avatar'))
{
    function avatar()
    {
        $CI =& get_instance();
        $avatar = $CI->session->userdata('avatar');
        $avatar_file = base_url().'media/'.$avatar;
        $avatar_default = base_url().'media/avatar.png';
        
        return $v = !empty($avatar) ? $avatar_file : $avatar_default;
    }
}

if(!function_exists('role'))
{
    function role()
    {
        $CI =& get_instance();
        $role = $CI->session->userdata('role');

        return $v = isset($role) ? $role : 0;
    }
}

if(!function_exists("lastlogin"))
{
    function lastlogin()
    {
        $CI =& get_instance();
        $lastlogin = $CI->session->userdata('lastlogin');

        return $v = ($lastlogin) ? format_date($lastlogin,'F d, Y H:i:s') : "";
    }
}
/* end fungtion session 
 * -------------------------------------------------
 */

if(!function_exists("get_tag_p"))
{
    function get_tag_p($html = '',$index=false,$element=null,$is_text=false)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $domx = new DOMXPath($dom);
        $entries = $domx->evaluate("//p");
        $arr = array();
        foreach ($entries as $entry) {
         if(!empty($entry->nodeValue) and strlen($entry->nodeValue) > 9)
            if($is_text)
                $arr[] = $entry->nodeValue;
            else
                $arr[] = '<' . $entry->tagName . ' '.$element.'>' . $entry->nodeValue .  '</' . $entry->tagName . '>';
        }
        
        if($index)
            return $data = isset($arr[$index-1]) ? $arr[$index-1] : '';
        else
            return $arr;
    }
}

if(!function_exists('contains'))
{
    function contains($string = NULL, $value = FALSE, $explode = ",")
    {
        $ststus = FALSE;
        if(!empty ($string) && $value)
        {
            $rows = explode(',', $string);
            foreach ($rows as $val)
            {
                if($val == $value){
                    $ststus = TRUE;
                }
            }
        }

        return $ststus;
    }
}

if(!function_exists("setting"))
{
    function setting($key = '')
    {
        $CI =& get_instance();
        $CI->load->model('setting_mod');

        $value = $CI->setting_mod->get_value($key);
        return $value;
    }
}


/*
 * @function untuk menampilkan menu dinamis di Header
 */
if(!function_exists("categorys"))
{
    function categorys()
    {
        $CI =& get_instance();
        $CI->load->model('product_mod');
        
        return $CI->product_mod->get_categories();
    }
}

if(!function_exists("media"))
{
    function media($user_id)
    {
        $CI =& get_instance();
        $CI->load->model('user_mod');
        
        $media =array();
        $data = $CI->user_mod->get_media($user_id);
        
        if(!empty($data->facebook_id)){
            $media[] = "Facebook";
        }
        if(!empty($data->twitter_id)){
            $media[] = "Twitter";
        }
        if(!empty($data->google_id)){
            $media[] = "Youtube";
        }
        if(!empty($data->instagram_id)){
            $media[] = "Instagram";
        }
        if(!empty($data->vine_id)){
            $media[] = "Vine";
        }

        return $media;
}
}
/*
 * =============================================================================
 * END FUNCTION CUSTOM
 * =============================================================================
 */

if ( ! function_exists('xss_clean'))
{
    function xss_clean($post){
        $ci =& get_instance();
        return htmlspecialchars($ci->security->xss_clean($post));
    }
}


if(!function_exists("json_output"))
{
    function json_output($response)
    {
        return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if(!function_exists("set_send_mail"))
{
    function set_send_mail($data,$debug=FALSE)
    {
        $CI =& get_instance();
        
        // $config = Array(
        //     'protocol'  => 'smtp',
        //     'smtp_host' => 'smtp.gmail.com',
        //     // 'smtp_crypto' => 'ssl',
        //     'smtp_port' => '465', //465
        //     'smtp_user' => 'scarecrow646@gmail.com',
        //     'smtp_pass' => 'qq19101992',
        //     'smtp_from_name' => 'PWaktu',
        //     'mailtype'  => 'html',
        //     'wordwrap'  => TRUE,
        //     'charset'   => 'utf-8',
        //     // 'starttls'  => true,
        //     'newline'   => "\r\n"
        // );

        $config = Array(
            'protocol'  => 'mail',
            'smtp_host' => 'mail.derrmaflabs.com',
            'smtp_port' => '465', //465
            'smtp_user' => 'info@derrmaflabs.com',
            'smtp_pass' => 'paruhwaktuload2018',
            'smtp_from_name' => 'PWaktu',
            'mailtype'  => 'html',
            'wordwrap'  => TRUE,
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        );
         
        $CI->load->library('email');
        // $CI->email->set_newline("\r\n");
        $CI->email->initialize($config);

        $CI->email->from($data['from'], $data['name']);
        $CI->email->to($data['to']);
        $CI->email->subject($data['subject']);
        $CI->email->message($data['message']);

        $is_send = $CI->email->send();

        if($debug) {
            echo $CI->email->print_debugger();
        }
        else{
            return $is_send;
        }
    }
}

if(!function_exists('pre'))
{
    function pre($data, $next = 0){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if(!$next){ exit; }
    }
}

if(!function_exists('send_message'))
{
    function send_message($number, $message){
        // purindo.net - akun : Kyky - pass:asdasd
         $token = "b4b643cb1547b52057fxrf15336158188a"; // masukkan token anda 
         $passkey = "juyxx"; // masukkan passkey anda
         $nama = "ParoWaktu"; // nama di phone book
              
              $ch = curl_init();
              $fields = array(
                  'token'=>$token,
                  'aksi'=>'1',        // aksi  = 1 kirim sms ,  aksi = 2 cek saldo dan masa aktif ,  aksi = 3 lihat sms outbox ,   aksi = 4 add phone book
                  'pesan'=> $message,
                  'hp'=>$number,
                  'passkey'=> $passkey,    
                  'nama'=> $nama,
              );
              $postvars = json_encode($fields);
              $url = "http://purindo.net/api/sms.php";
              
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        $result = curl_exec($ch);
    }
}