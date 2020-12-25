<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * Responsible for localiazing custom fields.<br/>
 * It uses browser's "HTTP_ACCEPT_LANGUAGE" as well as WMPL (if it is available) "ICL_LANGUAGE_CODE"<br/>
 * to determine the current locale and If the field has corresponding language value it substite otherwise<br/>
 * default value will be used (which is "en" ofcourse)
 * 
 * @author Saravana Kumar K
 * @copyright Sarkware Pvt Ltd
 *
 */
class Wcff_Locale {
    
    private $locales = array(
        "af" => array("title" => "Afrikaans", "lcid" => array("af", "af_NA", "af_ZA")),
        "ak" => array("title" => "Akan", "lcid" => array("ak", "ak_GH")),
        "sq" => array("title" => "Albanian", "lcid" => array("sq", "sq_AL")),
        "am" => array("title" => "Amharic", "lcid" => array("am", "am_ET")),
        "ar" => array("title" => "Arabic", "lcid" => array("ar", "ar_DZ", "ar_BH", "ar_EG", "ar_IQ", "ar_JO", "ar_KW", "ar_LB", "ar_LY", "ar_MA", "ar_OM", "ar_QA", "ar_SA", "ar_SD", "ar_SY", "ar_TN", "ar_AE", "ar_YE")),
        "hy" => array("title" => "Armenian", "lcid" => array("hy", "hy_AM")),
        "as" => array("title" => "Assamese", "lcid" => array("as", "as_IN")),
        "asa" => array("title" => "Asu", "lcid" => array("asa", "asa_TZ")),
        "az" => array("title" => "Azerbaijani", "lcid" => array("az", "az_Cyrl", "az_Cyrl_AZ", "az_Latn", "az_Latn_AZ")),
        "bm" => array("title" => "Bambara", "lcid" => array("bm", "bm_ML")),
        "eu" => array("title" => "Basque", "lcid" => array("eu", "eu_ES")),
        "be" => array("title" => "Belarusian", "lcid" => array("be", "be_BY")),
        "bem" => array("title" => "Bemba", "lcid" => array("bem", "bem_ZM")),
        "bez" => array("title" => "Bena", "lcid" => array("bez", "bez_TZ")),
        "bn" => array("title" => "Bengali", "lcid" => array("bn", "bn_BD", "bn_IN")),
        "bs" => array("title" => "Bosnian", "lcid" => array("bs", "bs_BA")),
        "bg" => array("title" => "Bulgarian", "lcid" => array("bg", "bg_BG")),
        "my" => array("title" => "Burmese", "lcid" => array("my", "my_MM")),
        "ca" => array("title" => "Catalan", "lcid" => array("ca", "ca_ES")),        
        "tzm" => array("title" => "Central Morocco Tamazight", "lcid" => array("tzm", "tzm_Latn", "tzm_Latn_MA")),
        "chr" => array("title" => "Cherokee", "lcid" => array("chr", "chr_US")),
        "cgg" => array("title" => "Chiga", "lcid" => array("cgg", "cgg_UG")),        
        "zh" => array("title" => "Chinese", "lcid" => array("zh", "zh_Hans", "zh_Hans_CN", "zh_Hans_HK", "zh_Hans_MO", "zh_Hans_SG", "zh_Hant", "zh_Hant_HK", "zh_Hant_MO", "zh_Hant_TW")),
        "kw" => array("title" => "Cornish", "lcid" => array("kw", "kw_GB")),
        "hr" => array("title" => "Croatian", "lcid" => array("hr", "hr_HR")),
        "cs" => array("title" => "Czech", "lcid" => array("cs", "cs_CZ")),
        "da" => array("title" => "Danish", "lcid" => array("da", "da_DK")),        
        "nl" => array("title" => "Dutch", "lcid" => array("nl", "nl_BE", "nl_NL")),
        "ebu" => array("title" => "Embu", "lcid" => array("ebu", "ebu_KE")),        
        "en" => array("title" => "English", "lcid" => array("en", "en_AS", "en_AU", "en_BE", "en_BZ", "en_BW", "en_CA", "en_GU", "en_HK", "en_IN", "en_IE", "en_JM", "en_MT", "en_MH", "en_MU", "en_NA", "en_NZ", "en_MP", "en_PK", "en_PH", "en_SG", "en_ZA", "en_TT", "en_UM", "en_VI", "en_GB", "en_US", "en_ZW")),
        "eo" => array("title" => "Esperanto", "lcid" => array("eo")),
        "et" => array("title" => "Estonian", "lcid" => array("et", "et_EE")),
        "ee" => array("title" => "Ewe", "lcid" => array("ee", "ee_GH", "ee_TG")),
        "fo" => array("title" => "Faroese", "lcid" => array("fo", "fo_FO")),
        "fil" => array("title" => "Filipino", "lcid" => array("fil", "fil_PH")),
        "fi" => array("title" => "Finnish", "lcid" => array("fi", "fi_FI")),
        "fr" => array("title" => "French", "lcid" => array("fr", "fr_BE", "fr_BJ", "fr_BF", "fr_BI", "fr_CM", "fr_CA", "fr_CF", "fr_TD", "fr_KM", "fr_CG", "fr_CD", "fr_CI", "fr_DJ", "fr_GQ", "fr_FR", "fr_GA", "fr_GP", "fr_GN", "fr_LU", "fr_MG", "fr_ML", "fr_MQ", "fr_MC", "fr_NE", "fr_RW", "fr_RE", "fr_BL", "fr_MF", "fr_SN", "fr_CH", "fr_TG")),
        "ff" => array("title" => "Fulah", "lcid" => array("ff", "ff_SN")),
        "gl" => array("title" => "Galician", "lcid" => array("gl", "gl_ES")),
        "lg" => array("title" => "Ganda", "lcid" => array("lg", "lg_UG")),
        "ka" => array("title" => "Georgian", "lcid" => array("ka", "ka_GE")),
        "de" => array("title" => "German", "lcid" => array("de", "de_AT", "de_BE", "de_DE", "de_LI", "de_LU", "de_CH")),        
        "el" => array("title" => "Greek", "lcid" => array("el", "el_CY", "el_GR")),
        "gu" => array("title" => "Gujarati", "lcid" => array("gu", "gu_IN")),
        "guz" => array("title" => "Gusii", "lcid" => array("guz", "guz_KE")),        
        "ha" => array("title" => "Hausa", "lcid" => array("ha", "ha_Latn", "ha_Latn_GH", "ha_Latn_NE", "ha_Latn_NG")),
        "haw" => array("title" => "Hawaiian", "lcid" => array("haw", "haw_US")),
        "he" => array("title" => "Hebrew", "lcid" => array("he", "he_IL")),
        "hi" => array("title" => "Hindi", "lcid" => array("hi", "hi_IN")),
        "hu" => array("title" => "Hungarian", "lcid" => array("hu", "hu_HU")),
        "is" => array("title" => "Icelandic", "lcid" => array("is", "is_IS")),
        "ig" => array("title" => "Igbo", "lcid" => array("ig", "ig_NG")),
        "id" => array("title" => "Indonesian", "lcid" => array("id", "id_ID")),
        "ga" => array("title" => "Irish", "lcid" => array("ga", "ga_IE")),       
        "it" => array("title" => "Italian", "lcid" => array("it", "it_IT", "it_CH")),
        "ja" => array("title" => "Japanese", "lcid" => array("ja", "ja_JP")),
        "kea" => array("title" => "Kabuverdianu", "lcid" => array("kea", "kea_CV")),
        "kab" => array("title" => "Kabyle", "lcid" => array("kab", "kab_DZ")),
        "kl" => array("title" => "Kalaallisut", "lcid" => array("kl", "kl_GL")),
        "kln" => array("title" => "Kalenjin", "lcid" => array("kln", "kln_KE")),
        "kam" => array("title" => "Kamba", "lcid" => array("kam", "kam_KE")),
        "kn" => array("title" => "Kannada", "lcid" => array("kn", "kn_IN")),        
        "kk" => array("title" => "Kazakh", "lcid" => array("kk", "kk_Cyrl", "kk_Cyrl_KZ")),
        "km" => array("title" => "Khmer", "lcid" => array("km", "km_KH")),
        "ki" => array("title" => "Kikuyu", "lcid" => array("ki", "ki_KE")),
        "rw" => array("title" => "Kinyarwanda", "lcid" => array("rw", "rw_RW")),
        "kok" => array("title" => "Konkani", "lcid" => array("kok", "kok_IN")),
        "ko" => array("title" => "Korean", "lcid" => array("ko", "ko_KR")),
        "khq" => array("title" => "Koyra Chiini", "lcid" => array("khq", "khq_ML")),
        "ses" => array("title" => "Koyraboro Senni", "lcid" => array("ses", "ses_ML")),
        "lag" => array("title" => "Langi", "lcid" => array("lag", "lag_TZ")),
        "lv" => array("title" => "Latvian", "lcid" => array("lv", "lv_LV")),
        "lt" => array("title" => "Lithuanian", "lcid" => array("lt", "lt_LT")),
        "luo" => array("title" => "Luo", "lcid" => array("luo", "luo_KE")),
        "luy" => array("title" => "Luyia", "lcid" => array("luy", "luy_KE")),
        "mk" => array("title" => "Macedonian", "lcid" => array("mk", "mk_MK")),
        "jmc" => array("title" => "Machame", "lcid" => array("jmc", "jmc_TZ")),
        "kde" => array("title" => "Makonde", "lcid" => array("kde", "kde_TZ")),
        "mg" => array("title" => "Malagasy", "lcid" => array("mg", "mg_MG")),
        "ms" => array("title" => "Malay", "lcid" => array("ms", "ms_BN", "ms_MY")),
        "ml" => array("title" => "Malayalam", "lcid" => array("ml", "ml_IN")),
        "mt" => array("title" => "Maltese", "lcid" => array("mt", "mt_MT")),
        "gv" => array("title" => "Manx", "lcid" => array("gv", "gv_GB")),
        "mr" => array("title" => "Marathi", "lcid" => array("mr", "mr_IN")),
        "mas" => array("title" => "Masai", "lcid" => array("mas", "mas_KE", "mas_TZ")),
        "mer" => array("title" => "Meru", "lcid" => array("mer", "mer_KE")),
        "mfe" => array("title" => "Morisyen", "lcid" => array("mfe", "mfe_MU")),
        "naq" => array("title" => "Nama", "lcid" => array("naq", "naq_NA")),
        "ne" => array("title" => "Nepali", "lcid" => array("ne", "ne_IN", "ne_NP")),
        "nd" => array("title" => "North Ndebele", "lcid" => array("nd", "nd_ZW")),
        "nb" => array("title" => "Norwegian Bokmål", "lcid" => array("nb", "nb_NO")),
        "nn" => array("title" => "Norwegian Nynorsk", "lcid" => array("nn", "nn_NO")),
        "nyn" => array("title" => "Nyankole", "lcid" => array("nyn", "nyn_UG")),
        "or" => array("title" => "Oriya", "lcid" => array("or", "or_IN")),
        "om" => array("title" => "Oromo", "lcid" => array("om", "om_ET", "om_KE")),
        "ps" => array("title" => "Pashto", "lcid" => array("ps", "ps_AF")),        
        "fa" => array("title" => "Persian", "lcid" => array("fa", "fa_AF", "fa_IR")),
        "pl" => array("title" => "Polish", "lcid" => array("pl", "pl_PL")),
        "pt" => array("title" => "Portuguese", "lcid" => array("pt", "pt_BR", "pt_GW", "pt_MZ", "pt_PT")),
        "pa" => array("title" => "Punjabi", "lcid" => array("pa", "pa_Arab", "pa_Arab_PK", "pa_Guru", "pa_Guru_IN")),        
        "ro" => array("title" => "Romanian", "lcid" => array("ro", "ro_MD", "ro_RO")),
        "rm" => array("title" => "Romansh", "lcid" => array("rm", "rm_CH")),
        "rof" => array("title" => "Rombo", "lcid" => array("rof", "rof_TZ")),
        "ru" => array("title" => "Russian", "lcid" => array("ru", "ru_MD", "ru_RU", "ru_UA")),
        "rwk" => array("title" => "Rwa", "lcid" => array("rwk", "rwk_TZ")),
        "saq" => array("title" => "Samburu", "lcid" => array("saq", "saq_KE")),
        "sg" => array("title" => "Sango", "lcid" => array("sg", "sg_CF")),
        "seh" => array("title" => "Sena", "lcid" => array("seh", "seh_MZ")),
        "sr" => array("title" => "Serbian", "lcid" => array("sr", "sr_Cyrl", "sr_Cyrl_BA", "sr_Cyrl_ME", "sr_Cyrl_RS", "sr_Latn", "sr_Latn_BA", "sr_Latn_ME", "sr_Latn_RS")),
        "sn" => array("title" => "Shona", "lcid" => array("sn", "sn_ZW")),
        "ii" => array("title" => "Sichuan Yi", "lcid" => array("ii", "ii_CN")),
        "si" => array("title" => "Sinhala", "lcid" => array("si", "si_LK")),
        "sk" => array("title" => "Slovak", "lcid" => array("sk", "sk_SK")),
        "sl" => array("title" => "Slovenian", "lcid" => array("sl", "sl_SI")),
        "xog" => array("title" => "Soga", "lcid" => array("xog", "xog_UG")),
        "so" => array("title" => "Somali", "lcid" => array("so", "so_DJ", "so_ET", "so_KE", "so_SO")),        
        "es" => array("title" => "Spanish", "lcid" => array("es", "es_AR", "es_BO", "es_CL", "es_CO", "es_CR", "es_DO", "es_EC", "es_SV", "es_GQ", "es_GT", "es_HN", "es_419", "es_MX", "es_NI", "es_PA", "es_PY", "es_PE", "es_PR", "es_ES", "es_US", "es_UY", "es_VE")),
        "sw" => array("title" => "Swahili", "lcid" => array("sw", "sw_KE", "sw_TZ")),
        "sv" => array("title" => "Swedish", "lcid" => array("sv", "sv_FI", "sv_SE")),
        "gsw" => array("title" => "Swiss German", "lcid" => array("gsw", "gsw_CH")),
        "shi" => array("title" => "Tachelhit", "lcid" => array("shi", "shi_Latn", "shi_Latn_MA", "shi_Tfng", "shi_Tfng_MA")),
        "dav" => array("title" => "Taita", "lcid" => array("dav", "dav_KE")),
        "ta" => array("title" => "Tamil", "lcid" => array("ta", "ta_IN", "ta_LK")),
        "te" => array("title" => "Telugu", "lcid" => array("te", "te_IN")),
        "teo" => array("title" => "Teso", "lcid" => array("teo", "teo_KE", "teo_UG")),
        "th" => array("title" => "Thai", "lcid" => array("th", "th_TH")),
        "bo" => array("title" => "Tibetan", "lcid" => array("bo", "bo_CN", "bo_IN")),
        "ti" => array("title" => "Tigrinya", "lcid" => array("ti", "ti_ER", "ti_ET")),
        "to" => array("title" => "Tonga", "lcid" => array("to", "to_TO")),
        "tr" => array("title" => "Turkish", "lcid" => array("tr", "tr_TR")),
        "uk" => array("title" => "Ukrainian", "lcid" => array("uk", "uk_UA")),
        "ur" => array("title" => "Urdu", "lcid" => array("ur", "ur_IN", "ur_PK")),
        "uz" => array("title" => "Uzbek", "lcid" => array("uz", "uz_Arab", "uz_Arab_AF", "uz_Cyrl", "uz_Cyrl_UZ", "uz_Latn", "uz_Latn_UZ")),
        "vi" => array("title" => "Vietnamese", "lcid" => array("vi", "vi_VN")),
        "vun" => array("title" => "Vunjo", "lcid" => array("vun", "vun_TZ")),
        "cy" => array("title" => "Welsh", "lcid" => array("cy", "cy_GB")),
        "yo" => array("title" => "Yoruba", "lcid" => array("yo", "yo_NG")),
        "zu" => array("title" => "Zulu", "lcid" => array("zu", "zu_ZA"))        
    );
    
    public function __construct() {
        
    }
    
    /**
     * 
     * Returns the list of ISO 639-1 language codes<br/>
     * 
     * @return string[]|string[][]
     * 
     */
    public function get_locales() {
    	$llist = array();
    	foreach ($this->locales as $code => $lcid) {
    		if ($code != "en") {
    			$llist[$code] = $lcid["title"];
    		}
    	}
    	return $llist;
    }
    
    /**
     * 
     * Map any language codes (ISO or LCID) to the parent ISO code<br/>
     * eg. if the code is "en_US" it will return "en"
     * 
     * @param string $_code
     * @return string
     * 
     */
    public function check_locale($_code) {
    	foreach ($this->locales as $code => $lcids) {
    		if (in_array($_code, $lcids["lcid"])) {
    			return $code;
    		}
    	}
    	/* Not able to determine the locale - safe to return default */
    	return "en";
    }
    
    /**
     * 
     * Detect the current locale of the Browser
     * 
     * @return string
     * 
     */
    public function detrmine_current_locale() {
    	$locale = "en";
    	/* Locale from Browser */
    	if(function_exists("locale_accept_from_http")) {
    		$locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    	} else {
    		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    			$locale = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    		}
    	}
    	
    	/* Check if WPML is installed and active
    	 * if it is then use that instead */
    	if(defined('ICL_LANGUAGE_CODE')){
    		$locale = ICL_LANGUAGE_CODE;
    	}
    	
    	/* Right now WC Fields Factory does support all ISO languages (codes)
    	 * but not the locale variants for a single language,
    	 * So we have to map all the locales to ISO codes, lilke 'en_US' will be just 'en' */
    	if($locale) {
    		$locale = $this->check_locale($locale);
    	} else {
    		$locale = "en";
    	}
    	return $locale;
    }
    
    /**
     *
     * If multilingual option is enabled, then this method will translate<br/>
     * all the translatable config option to the current locale.<br/>
     * It determine the current local by trying to read the 'HTTP_ACCEPT_LANGUAGE' constance.<br/>
     * Also the it tries to read the 'ICL_LANGUAGE_CODE' constance o WPML<br/>
     * If the later exist then that will be used as current locale.
     *
     * @param object $_field
     * @return object
     *
     */
    public function localize_field($_field) {
    	/* Determine the current locale */
    	$locale = $this->detrmine_current_locale();
    	
    	/* Well start the translation */
    	if($locale != "en") {
    		if(isset($_field["locale"]) && isset($_field["locale"][$locale])) {
    			$resources = $_field["locale"][$locale];
    			if($resources) {
    				foreach ($_field as $key => $value) {
    					if($key != "locale") {
    						if(isset($resources[$key]) && $resources[$key] != "") {
    							$_field[$key] = $resources[$key];
    						}
    					}
    				}
    			}
    			/* Remove locale property from the $_field argument
    			 * as it is no longer needed */
    			//unset($_field["locale"]);
    		}
    	}
    	
    	return $_field;
    }
    
}

?>