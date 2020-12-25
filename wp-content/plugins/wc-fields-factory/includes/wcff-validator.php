<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * Perform validation for product fields, especially at the time of Add To Cart
 * 
 * @author : Saravana Kumar K
 * @copyright : Sarkware Pvt Ltd
 *
 */
class Wcff_Validator {
    
    private $pid = null;
    private $passed = null;
    private $file_size_ok = null;
    
    private $fields_cloning = "no";
    private $product_fields = null;
    private $admin_fields = null;
    private $repeater_fields = null;
    
    public function __construct() {}
    
    public function validate($_pid, $_passed) {   
        	$this->pid = $_pid;
        	$this->passed = $_passed; 
        	$this->file_size_ok = true;
        	
        if ($this->pid) {            
            $wccpf_options = wcff()->option->get_options();
            $this->fields_cloning = isset($wccpf_options["fields_cloning"]) ? $wccpf_options["fields_cloning"] : "no";
            
            $this->product_fields = wcff()->dao->load_fields_for_product($this->pid, 'wccpf');
            $this->admin_fields = wcff()->dao->load_fields_for_product($this->pid, 'wccaf', 'any');
            if( isset( $_REQUEST ) && isset( $_REQUEST["variation_id"] ) && $_REQUEST["variation_id"] != 0 && !empty($_REQUEST["variation_id"]) ){
            	$this->product_fields = array_merge( $this->product_fields, wcff()->dao->load_fields_for_product($_REQUEST["variation_id"], 'wccpf', 'cart-page') );
            	$this->admin_fields = array_merge( $this->admin_fields, wcff()->dao->load_fields_for_product($_REQUEST["variation_id"], 'wccaf', 'cart-page') );
            }
            /* Before check validation to remove field rule not applicable field from product fields */
            if(  $this->fields_cloning == "no" ){
                $this->remove_field_rule_is_hidden();
            } else {
                
            }
            /* Perform validation on product fields */
            $this->validate_product_fields();
            /* Perform validation for admin fields */
            $this->validate_admin_fields();
        }        
        return $this->passed;        
    }
    
    public function validate_immediate($_pid, $_field, $_name, $_value) {
        $this->pid = $_pid;
        $this->passed = true;
        $this->file_size_ok = true;
        if (method_exists($this, "validate_". $_field["type"]."_field")) {
            return call_user_func(array($this, "validate_". $_field["type"]."_field"), $_field, $_value);
        }
        return true;
    }
    
    /* To remove unwanted fields from product field */
    private function remove_field_rule_is_hidden(){
        for( $x = 0; $x < count( $this->product_fields ); $x++ ){
           foreach ( $this->product_fields[$x] as $fields) {
               if( isset( $fields["field_rules"] ) && count( $fields["field_rules"] ) ){
                   $fname   = $fields["name"];
                   $ftype   = $fields["type"];
                   $dformat = isset( $fields["format"] ) ? $fields["format"] : "";
                   $uvalue  = isset( $_REQUEST[$fname] ) ? $_REQUEST[$fname] : "";
                   $p_rules = $fields["field_rules"];
                   /* Iterate through the rules and update the price */
                   foreach ( $p_rules as $prule ) {
                       if ( !wcff()->negotiator->check_rules ( $prule, $uvalue, $ftype, $dformat ) ) {
                           foreach( $prule["field_rules"] as $each_f_k => $each_f_v ){
                               if( $each_f_v == "show" ){
                                   for( $p = 0; $p < count( $this->product_fields ); $p++ ){
                                       foreach ( $this->product_fields[$p] as $key_infield => $infield) {
                                           if( $infield["name"] == $each_f_k ){
                                             unset( $this->product_fields[$p][$key_infield] );
                                           }
                                       }
                                   }
                               }
                           }
                       }
                   }
               }
           }
        }
    }
   
    
    /**
     * 
     * Loop through all the Product Fields as well as Admin Fields
     * and perform the validtaion for each ( If configured so )
     * 
     */
    private function validate_product_fields() {
        if ($this->fields_cloning == "no") {
            /* Validation loop for Product Fields */
            foreach ($this->product_fields as $fields) {
                if (is_array($fields) && count($fields)) {
                    foreach ($fields as $field) {
                        $res = true;
                        $field["required"] = isset($field["required"]) ? $field["required"] : "no";
                        /* Proceed only if the field is mandatory */
                        if ($field["required"] == "yes" || $field["type"] == "file") {
                            if ($field["type"] != "file") {
                            	if (isset($_REQUEST[$field["name"]])) {
                            		$res = call_user_func(array(
                            			$this,
                            			"validate_" . $field["type"] . "_field"
                            		), $field, $_REQUEST[$field["name"]]);
                            	} else {
                            		$res = false;
                            	}                                
                            } else {
                            	if (isset($_FILES[$field["name"]])) {
                            		$res = call_user_func(array(
                            			$this,
                            			"validate_" . $field["type"] . "_field"
                            		), $field, $_FILES[$field["name"]]);
                            	} else {
                            		$res = false;
                            	}                                
                            }
                        }
                        if (! $res || ! $this->file_size_ok) {
                            $this->passed = false;
                            wc_add_notice(! $this->file_size_ok ? "Upload size limit exceed, Allow size is " . $field["max_file_size"] . "kb.!" : $field["message"], 'error');
                        }
                    }
                }
            }
        } else {
            /* Validation loop for Product Fields - with cloning enabled */
            if (isset($_REQUEST["quantity"])) {
                $pcount = intval($_REQUEST["quantity"]);
                foreach ($this->product_fields as $title => $fields) {
                    if (is_array($fields) && count($fields)) {
                        foreach ($fields as $field) {
                            $res = true;
                            $field["required"] = isset($field["required"]) ? $field["required"] : "no";
                            if ($field["required"] == "yes" || $field["type"] == "file") {
                                for ($i = 1; $i <= $pcount; $i ++) {
                                    if ($field["type"] != "file") {
                                    	if (isset($_REQUEST[$field["name"] . "_" . $i])) {
                                    		$res = call_user_func(array(
                                    			$this,
                                    			"validate_" . $field["type"] . "_field"
                                    		), $field, $_REQUEST[$field["name"] . "_" . $i]);
                                    	} else {
                                    		$res = false;
                                    	}                                        
                                    } else {
                                    	if (isset($_FILES[$field["name"] . "_" . $i])) {
                                    		$res = call_user_func(array(
                                    			$this,
                                    			"validate_" . $field["type"] . "_field"
                                    		), $field, $_FILES[$field["name"] . "_" . $i]);
                                    	} else {
                                    		$res = false;
                                    	}                                        
                                    }
                                }
                            }
                            if (! $res || ! $this->file_size_ok) {
                                $this->passed = false;
                                wc_add_notice(! $this->file_size_ok ? "Upload size limit exceed, Allow size is " . $field["max_file_size"] . "kb.!" : $field["message"], 'error');
                            }
                        }
                    }
                }
            }
        }
    }
    
    private function validate_admin_fields() {
        if ($this->fields_cloning == "no") {
            /* Validation loop for Admin Fields might be rendered on single product page */
            foreach ($this->admin_fields as $title => $afields) {
                if (is_array($afields) && count($afields) > 0) {
                    foreach ($afields as $key => $afield) {
                        $res = true;
                        $afield["show_on_product_page"] = isset($afield["show_on_product_page"]) ? $afield["show_on_product_page"] : "no";
                        if ($afield["show_on_product_page"] == "yes" && $afield["required"] == "yes") {
                            $res = call_user_func(array(
                                $this,
                                "validate_" . $afield["type"] . "_field"
                            ), $afield, $_REQUEST[$afield["name"]]);
                        }
                        if (! $res) {
                            $this->passed = false;
                            wc_add_notice($afield["message"], 'error');
                        }
                    }
                }
            }
        } else {
            /* Validation loop for Admin Fields might be rendered on single product page - with cloning enabled */
            if (isset($_REQUEST["quantity"])) {
                $pcount = intval($_REQUEST["quantity"]);
                foreach ($this->admin_fields as $title => $afields) {
                    if (is_array($afields) && count($afields) > 0) {
                        foreach ($afields as $key => $afield) {
                            $res = true;
                            $afield["show_on_product_page"] = isset($afield["show_on_product_page"]) ? $afield["show_on_product_page"] : "no";
                            if ($afield["show_on_product_page"] == "yes" && $afield["required"] == "yes") {
                                for ($i = 1; $i <= $pcount; $i ++) {
                                    $res = call_user_func(array(
                                        $this,
                                        "validate_" . $afield["type"] . "_field"
                                    ), $afield, $_REQUEST[$afield["name"] . "_" . $i]);
                                }
                            }
                            if (! $res) {
                                $this->passed = false;
                                wc_add_notice($afield["message"], 'error');
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * 
     * Check whether the submitted text field has value
     * 
     * @param object $_field
     * @param string $_val
     * @return boolean
     * 
     */
    private function validate_text_field($_field, $_val) {
    	    return (isset($_val) && !empty($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted textarea field has value
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_textarea_field($_field, $_val) {
        	return (isset($_val) && !empty($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted number field has value
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_number_field($_field, $_val) {
        	return (isset($_val) && is_numeric($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted email field has value
     * it also check for the email address format 
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_email_field($_field, $_val) {
    	    return (isset($_val) && !filter_var($_val, FILTER_VALIDATE_EMAIL) === false) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted date field has value
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_datepicker_field($_field, $_val) {
    	    return (isset($_val) && !empty($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted color field has value
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_colorpicker_field($_field, $_val) {
        	return (isset($_val) && !empty($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted checkbox field has a valid arrays of options
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_checkbox_field($_field, $_val) {
    	    return (isset($_val) && !empty($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted radio field has value
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_radio_field($_field, $_val) {
    	    return (isset($_val) && !empty($_val)) ? true : false;
    }
    
    /**
     *
     * Check whether the submitted select field has value
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_select_field($_field, $_val) {
    	if (isset($_val) && !empty($_val)) {
    		if ($_val != "wccpf_none") {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     *
     * Check whether the submitted file field has value
     * it also check for two more aspect whether the submitted file has correct extension
     * as well as the size not exceed the specified max upload size.
     *
     * @param object $_field
     * @param string $_val
     * @return boolean
     *
     */
    private function validate_file_field($_field, $_val) {
        $res = true;
        $this->file_size_ok = true;
        $is_multi_file = isset($_field["multi_file"]) ? $_field["multi_file"] : "no";
        
        if ($is_multi_file == "yes") {
            $files = $_val;
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $res = $this->validate_file_upload($file, $_field['filetypes'], $_field["required"]);
                    if ($res && isset($files["size"])) {
                        $this->file_size_ok = $this->validate_file_upload_max_size($_field, $files["size"][0]);
                    }
                    if (! $res || ! $this->file_size_ok) {
                        break;
                    }
                }
            }
        } else {
            $res = $this->validate_file_upload($_val, $_field['filetypes'], $_field["required"]);
            if ($res && isset($_val["size"])) {
                $this->file_size_ok = $this->validate_file_upload_max_size($_field, $_val["size"]);
            }
        }
        
        return ($res && $this->file_size_ok);
    }
    
    function validate_file_upload($_uploadedfile, $_file_types, $_mandatory) {
        $file_ok = false;
        $no_file = false;
        
        if (isset($_uploadedfile['error'])) {
            switch ($_uploadedfile['error']) {
                case UPLOAD_ERR_OK:
                    $file_ok = true;
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $no_file = true;
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $file_ok = false;
                default:
                    $file_ok = false;
            }
        }
        
        if ($file_ok && ! $no_file) {
            
            $file_ok = false;
            $filename = $_uploadedfile['name'];
            $mime_type = $this->get_mime_type($_uploadedfile);
            
            if ($_file_types && $_file_types != "") {
                if ((strpos($_file_types, "image/") !== false) && (strpos($mime_type, "image/") !== false)) {
                    $file_ok = true;
                } else if ((strpos($_file_types, "audio/") !== false) && (strpos($mime_type, "audio/") !== false)) {
                    $file_ok = true;
                } else if ((strpos($_file_types, "video/") !== false) && (strpos($mime_type, "video/") !== false)) {
                    $file_ok = true;
                } else {
                    $allowed_types = explode(',', $_file_types);
                    if (is_array($allowed_types)) {
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        if (in_array("." . $ext, $allowed_types) || $ext == "php") {
                            $file_ok = true;
                        }
                    }
                }
            } else {
                $file_ok = true;                
            }
        }
        
        if (! $no_file) {            
            return $file_ok;
        }
        
        if ($_mandatory == "no") {        
            return true;
        }
        
        return $file_ok;
    }
    
    function get_mime_type($_uploadedfile) {
        $mime_type = "";
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_uploadedfile["tmp_name"]);
        } else {
            $mimeTypes = $this->mime_types();
            $filename = $_uploadedfile["name"];
            $extension = end(explode('.', $filename));
            $mime_type = $mimeTypes[$extension];
        }
        return $mime_type;
    }

    private function validate_file_upload_max_size($_field, $_file_size) {
        $size_ok = true;
        if (isset($_field["max_file_size"]) && $_field["max_file_size"] != "") {            
            if (round($_field["max_file_size"]) < round($_file_size / 1024)) {
                $size_ok = false;
            }
        }
        return $size_ok;
    }
    
    /**
     * MIME list for file upload validation
     * @return string[]
     */
    private function mime_types() {
        return array(
            "323" => "text/h323",
            "acx" => "application/internet-property-stream",
            "ai" => "application/postscript",
            "aif" => "audio/x-aiff",
            "aifc" => "audio/x-aiff",
            "aiff" => "audio/x-aiff",
            "asf" => "video/x-ms-asf",
            "asr" => "video/x-ms-asf",
            "asx" => "video/x-ms-asf",
            "au" => "audio/basic",
            "avi" => "video/x-msvideo",
            "axs" => "application/olescript",
            "bas" => "text/plain",
            "bcpio" => "application/x-bcpio",
            "bin" => "application/octet-stream",
            "bmp" => "image/bmp",
            "c" => "text/plain",
            "cat" => "application/vnd.ms-pkiseccat",
            "cdf" => "application/x-cdf",
            "cer" => "application/x-x509-ca-cert",
            "class" => "application/octet-stream",
            "clp" => "application/x-msclip",
            "cmx" => "image/x-cmx",
            "cod" => "image/cis-cod",
            "cpio" => "application/x-cpio",
            "crd" => "application/x-mscardfile",
            "crl" => "application/pkix-crl",
            "crt" => "application/x-x509-ca-cert",
            "csh" => "application/x-csh",
            "css" => "text/css",
            "dcr" => "application/x-director",
            "der" => "application/x-x509-ca-cert",
            "dir" => "application/x-director",
            "dll" => "application/x-msdownload",
            "dms" => "application/octet-stream",
            "doc" => "application/msword",
            "dot" => "application/msword",
            "dvi" => "application/x-dvi",
            "dxr" => "application/x-director",
            "eps" => "application/postscript",
            "etx" => "text/x-setext",
            "evy" => "application/envoy",
            "exe" => "application/octet-stream",
            "fif" => "application/fractals",
            "flr" => "x-world/x-vrml",
            "gif" => "image/gif",
            "gtar" => "application/x-gtar",
            "gz" => "application/x-gzip",
            "h" => "text/plain",
            "hdf" => "application/x-hdf",
            "hlp" => "application/winhlp",
            "hqx" => "application/mac-binhex40",
            "hta" => "application/hta",
            "htc" => "text/x-component",
            "htm" => "text/html",
            "html" => "text/html",
            "htt" => "text/webviewhtml",
            "ico" => "image/x-icon",
            "ief" => "image/ief",
            "iii" => "application/x-iphone",
            "ins" => "application/x-internet-signup",
            "isp" => "application/x-internet-signup",
            "jfif" => "image/pipeg",
            "jpe" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "png" => "image/png",
            "js" => "application/x-javascript",
            "latex" => "application/x-latex",
            "lha" => "application/octet-stream",
            "lsf" => "video/x-la-asf",
            "lsx" => "video/x-la-asf",
            "lzh" => "application/octet-stream",
            "m13" => "application/x-msmediaview",
            "m14" => "application/x-msmediaview",
            "m3u" => "audio/x-mpegurl",
            "man" => "application/x-troff-man",
            "mdb" => "application/x-msaccess",
            "me" => "application/x-troff-me",
            "mht" => "message/rfc822",
            "mhtml" => "message/rfc822",
            "mid" => "audio/mid",
            "mny" => "application/x-msmoney",
            "mov" => "video/quicktime",
            "movie" => "video/x-sgi-movie",
            "mp2" => "video/mpeg",
            "mp4" => "video/mp4",
            "avi" => "video/avi",
            "mp3" => "audio/mpeg",
            "mpa" => "video/mpeg",
            "mpe" => "video/mpeg",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "mpp" => "application/vnd.ms-project",
            "mpv2" => "video/mpeg",
            "ms" => "application/x-troff-ms",
            "mvb" => "application/x-msmediaview",
            "nws" => "message/rfc822",
            "oda" => "application/oda",
            "p10" => "application/pkcs10",
            "p12" => "application/x-pkcs12",
            "p7b" => "application/x-pkcs7-certificates",
            "p7c" => "application/x-pkcs7-mime",
            "p7m" => "application/x-pkcs7-mime",
            "p7r" => "application/x-pkcs7-certreqresp",
            "p7s" => "application/x-pkcs7-signature",
            "pbm" => "image/x-portable-bitmap",
            "pdf" => "application/pdf",
            "pfx" => "application/x-pkcs12",
            "pgm" => "image/x-portable-graymap",
            "pko" => "application/ynd.ms-pkipko",
            "pma" => "application/x-perfmon",
            "pmc" => "application/x-perfmon",
            "pml" => "application/x-perfmon",
            "pmr" => "application/x-perfmon",
            "pmw" => "application/x-perfmon",
            "pnm" => "image/x-portable-anymap",
            "pot" => "application/vnd.ms-powerpoint",
            "ppm" => "image/x-portable-pixmap",
            "pps" => "application/vnd.ms-powerpoint",
            "ppt" => "application/vnd.ms-powerpoint",
            "prf" => "application/pics-rules",
            "ps" => "application/postscript",
            "pub" => "application/x-mspublisher",
            "qt" => "video/quicktime",
            "ra" => "audio/x-pn-realaudio",
            "ram" => "audio/x-pn-realaudio",
            "ras" => "image/x-cmu-raster",
            "rgb" => "image/x-rgb",
            "rmi" => "audio/mid",
            "roff" => "application/x-troff",
            "rtf" => "application/rtf",
            "rtx" => "text/richtext",
            "scd" => "application/x-msschedule",
            "sct" => "text/scriptlet",
            "setpay" => "application/set-payment-initiation",
            "setreg" => "application/set-registration-initiation",
            "sh" => "application/x-sh",
            "shar" => "application/x-shar",
            "sit" => "application/x-stuffit",
            "snd" => "audio/basic",
            "spc" => "application/x-pkcs7-certificates",
            "spl" => "application/futuresplash",
            "src" => "application/x-wais-source",
            "sst" => "application/vnd.ms-pkicertstore",
            "stl" => "application/vnd.ms-pkistl",
            "stm" => "text/html",
            "svg" => "image/svg+xml",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc" => "application/x-sv4crc",
            "t" => "application/x-troff",
            "tar" => "application/x-tar",
            "tcl" => "application/x-tcl",
            "tex" => "application/x-tex",
            "texi" => "application/x-texinfo",
            "texinfo" => "application/x-texinfo",
            "tgz" => "application/x-compressed",
            "tif" => "image/tiff",
            "tiff" => "image/tiff",
            "tr" => "application/x-troff",
            "trm" => "application/x-msterminal",
            "tsv" => "text/tab-separated-values",
            "txt" => "text/plain",
            "uls" => "text/iuls",
            "ustar" => "application/x-ustar",
            "vcf" => "text/x-vcard",
            "vrml" => "x-world/x-vrml",
            "wav" => "audio/x-wav",
            "wcm" => "application/vnd.ms-works",
            "wdb" => "application/vnd.ms-works",
            "wks" => "application/vnd.ms-works",
            "wmf" => "application/x-msmetafile",
            "wps" => "application/vnd.ms-works",
            "wri" => "application/x-mswrite",
            "wrl" => "x-world/x-vrml",
            "wrz" => "x-world/x-vrml",
            "xaf" => "x-world/x-vrml",
            "xbm" => "image/x-xbitmap",
            "xla" => "application/vnd.ms-excel",
            "xlc" => "application/vnd.ms-excel",
            "xlm" => "application/vnd.ms-excel",
            "xls" => "application/vnd.ms-excel",
            "xlsx" => "vnd.ms-excel",
            "xlt" => "application/vnd.ms-excel",
            "xlw" => "application/vnd.ms-excel",
            "xof" => "x-world/x-vrml",
            "xpm" => "image/x-xpixmap",
            "xwd" => "image/x-xwindowdump",
            "z" => "application/x-compress",
            "zip" => "application/zip"
        );
    }
    
}

?>