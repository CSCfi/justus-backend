<?php

class justus_auth {

    public $organization;
    public $domain;
    public $justusrole;
    public $uid;
    public $name;
    public $mail;

function __construct() {

    $this->uid = "";
    if (array_key_exists("shib-uid",$_SERVER)) {
  	$this->uid = $_SERVER["shib-uid"];
    }

    $this->mail = "";
    if (array_key_exists("shib-mail",$_SERVER)) {
  	$this->mail = $_SERVER["shib-mail"];
    }
    
    $this->name = "";
    if (array_key_exists("shib-givenName",$_SERVER) && array_key_exists("shib-sn",$_SERVER)) {
  	$this->name = $_SERVER["shib-givenName"]." ".$_SERVER["shib-sn"];
    }

    $domain = "";

    $this->organization = "";
    $this->justusrole = "";

    if (array_key_exists("shib-group",$_SERVER)) {
  	$groups = explode(";",$_SERVER["shib-group"]);
  	//print_r($groups);
  	for ($i=0; $i<count($groups); $i++) {
    	// a role for "owner"
    	if (strpos($groups[$i],'justus#group-admins')!==false) {
      	    $this->justusrole = "owner";
    	}

    	// a role for organizational admin
    	// - do we need group name from here?
    	if ($justusrole!="owner" && preg_match("/justus#([^;]*)-admins/",$groups[$i])==1) {
      	    $this->justusrole = "admin";
    	}

    	// mapping from allowed organizations here. defining membership.
    	if (strpos($groups[$i],'@')!==false && strpos($groups[$i],'@')==0) {
      	    $domain = $groups[$i];
      	    $domorgmap = [
        	'@arcada.fi' => '02535',
        	'@centria.fi' => '02536',
        	'@diak.fi' => '02623',
        	'@haaga-helia.fi' => '10056',
        	'@humak.fi' => '02631',
        	'@jamk.fi' => '02504',
        	'@kamk.fi' => '02473',
        	'@karelia.fi' => '02469',
        	// nb! xamk may have 3 domains (mahd. kyamk.fi ja mamk.fi)
        	'@xamk.fi' => '10118', '@kyamk.fi' => '10118', '@mamk.fi' => '10118',
        	'@lamk.fi' => '02470',
        	'@laurea.fi' => '02629',
        	'@metropolia.fi' => '10065',
        	'@samk.fi' => '02507',
        	'@seamk.fi' => '02472',
        	'@tamk.fi' => '02630',
        	'@novia.fi' => '10066',
        	'@polamk.fi' => '02557',
        	// tutkimusorganisaatio
        	'@fmi.fi' => '4940015',
        	// nb! mml has 2 domains
        	'@nls.fi' => '4020217', '@maanmittauslaitos.fi' => '4020217',
        	// nb! unknown organization for admin org
        	'@csc.fi' => '00000'
      	    ];

      	$this->organization = $domorgmap[$domain];

      	if ($justusrole!="owner" && $justusrole!="admin" && $organization) {
            $this->justusrole = "member";
      	}
    	}
    	}
    }


}
} // class ends

?>