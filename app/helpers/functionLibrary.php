<?php
/* PHP Function Library */

function s($count, $noun=false, $onePrep='', $pluralPrep='') {
  $s = ($count==1 or $noun ==='') ?'' :'s';
  $prep = ($count==1) ?$onePrep :$pluralPrep;
  if ($noun!='' and $noun!==false) $noun = ' '.$noun;
  if ($noun===false) {
    return $s;
  } else if ($prep) {
    return $prep.$noun.$s;
  } else {
    return $count.$noun.$s;
  }
}

function msg_display($session) {
	//Message and error display
	echo "<div id=\"messages\">";
	if (isset($session['msg'])) {
		$msg = $session['msg'];
		echo "<span class=\"msg\">$msg</span>", br;
	}
	if (isset($session['errors'])) {
		foreach ($session['errors'] as $err) {
			echo "<span class=\"error\">$err</span>", br;
		}
	}
	echo "</div>";
	unset($session['errors']);
	unset($session['msg']);
}

function file_extension($filename) {
    $path_info = pathinfo($filename);
    return $path_info['extension'];
}

function filename($filename) {
    $path_info = pathinfo($filename);
    if (!isset($path_info['extension'])) {
    	$matches[0]="";
    } else {
        preg_match("/([^?]*)/", $path_info['extension'], $matches);
    }
    return $path_info['filename'] ."." .$matches[0];
}

function checknull($fld) {
    $fld = trim($fld);
    if (empty($fld)) $fld = 'NULL';
    return $fld;
}

/* Escape characters and shorten the input string */
function clean($input, $maxlength) {
  $input = substr($input, 0, $maxlength);
  $input = EscapeShellCmd($input);
  return ($input);
}

function isVisible($array)  {
	return (count($array)==0) ?'none' :'visible';
}

function isSelected($actualValue, $selValue)  {
	return ($actualValue==$selValue) ?' selected' :"";
}

function isSelectedSet($set, $setEntry)  {
	return ($set & $setEntry) ?' checked' :'';
}

function boxChecked($fieldName) {
	return ($fieldName == "on") ?'checked' :'';
}

function radioChecked($fieldName, $radioValue) {
	return ($fieldName == $radioValue) ?'checked' :'';
}

function absoluteUrl($relativePageName) {
    global $appDir;
    $old = 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/'.$relativePageName;
    $new = 'http://'.$_SERVER['HTTP_HOST'].'/'.$sg_appDir.$relativePageName;
    return $new;
}

function getCity ($str) {
	$comma = strpos($str, ',');
	$city = ($comma === false) ?trim($str) :trim(substr($str, 0, $comma));
	return strtoupper(substr($city, 0, 1)).strtolower(substr($city, 1));
}

function getState ($str) {
	$comma = strpos($str, ',');
	return ($comma === false) ?'' :stateCode(trim(substr($str, $comma+1)));
}

function stateCode($state) {
	$states = ('AL,Alabama;AK,Alaska;AZ,Arizona;AR,Arkansas;CA,California;CT,Connecticut;DE,Delaware;DC,D.C.;FL,Florida;GA,Georgia;HI,Hawaii;ID,Idaho;IL,Illinois;IN,Indiana;IA,Iowa;KS,Kansas;KY,Kentucky;LA,Louisiana;ME,Maine;MD,Maryland;MA,Massachusetts;MI,Michigan;MN,Minnesota;MS,Mississippi;MO,Missouri;MT,Montana;NE,Nebraska;NV,Nevada;NH,New Hampshire;NM,New Mexico;NJ,New Jersey;NY,New York;NC,North Carolina;ND,North Dakota;OH,Ohio;OK,Oklahoma;OR,Oregon;PA,Pennsylvania;RI,Rhode Island;SC,South Carolina;SD,South Dakota;TN,Tennessee;TX,Texas;UT,Utah;VT,Vermont;VA,Virginia;WA,Washington;WV,West Virginia;WI,Wisconsin;WY,Wyoming;');
	if (substr($state,2,1)=='.') $state = substr($state,0,2);
	if (strlen($state) > 2) {
		$pos = strpos($states, strtoupper($state).';');
		$state = ($pos === false) ?false :substr($states, $pos-3, 2);
	} else {
		$pos = strpos($states, strtoupper($state).',');
		$state = ($pos === false) ?false :substr($states, $pos, 2);
	}
	return $state;
}

function makeStateSelector($selValue, $bsubmit) {
	$states = ('AL,Alabama;AK,Alaska;AZ,Arizona;AR,Arkansas;CA,California;CT,Connecticut;DE,Delaware;DC,D.C.;FL,Florida;GA,Georgia;HI,Hawaii;ID,Idaho;IL,Illinois;IN,Indiana;IA,Iowa;KS,Kansas;KY,Kentucky;LA,Louisiana;ME,Maine;MD,Maryland;MA,Massachusetts;MI,Michigan;MN,Minnesota;MS,Mississippi;MO,Missouri;MT,Montana;NE,Nebraska;NV,Nevada;NH,New Hampshire;NM,New Mexico;NJ,New Jersey;NY,New York;NC,North Carolina;ND,North Dakota;OH,Ohio;OK,Oklahoma;OR,Oregon;PA,Pennsylvania;RI,Rhode Island;SC,South Carolina;SD,South Dakota;TN,Tennessee;TX,Texas;UT,Utah;VT,Vermont;VA,Virginia;WA,Washington;WV,West Virginia;WI,Wisconsin;WY,Wyoming;');
    $sel = '<select name="state" size="1"';
    if ($bsubmit) $sel .= ' onChange="form.submit();">';
    else $sel .= '>';
    $sel .= '<option value=""'.isSelected($selValue, '').'>-- make selection --</option>';
    $pos = strpos($states, ',');
    while ($pos !== false) {
        $pos2 = strpos($states, ';', $pos);
        $state = substr($states, $pos-2, 2);
        $name = substr($states, $pos+1, $pos2-$pos-1);
        $sel .= '<option value="'.$state.'"'.isSelected($selValue, $state).'>'.$name.'</option>';
        $pos = strpos($states, ',', $pos+1);
    }
    $sel .= '</select>';
  	return $sel;
}

function sanitize($fld) {
    return strip_tags(addslashes($fld));
}
?>
