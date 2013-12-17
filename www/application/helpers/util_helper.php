<?php
/**
 * This file is a collection of miscellaneous utility functions
 */

//Some useful misc constants
define('NL', "<br>\n");

// Constants for date formats
define('DATEFMT_LONGDATE', 'F j, Y');
define('DATEFMT_LONGDATETIME', 'F j, Y h:i a');
define('DATEFMT_SHORTDATE', 'n/j/Y');
define('DATEFMT_SHORTDATETIME', 'n/j/Y h:i a');
define('DATEFMT_ABBREVDATETIME', 'D, d M Y h:i:s a');
define('DATEFMT_DBFORMAT', 'Y-m-d H:i:s');
define('DATEFMT_DBFORMAT_DATE_ONLY', 'Y-m-d');
define('DATEFMT_DBFORMAT_TIME_ONLY', 'H:i:s');
define('DATEFMT_FLASH', 'm/d/Y H:i:s');

/**
 * Display the contents of any variable.  Especially useful with arrays and objects.
 *
 * @param mixed $obj A variable whose contents are to be printed
 * @param string $color Any valid CSS color value.  It is optional and defaults to #000
 * @return void
 */
function debug_print($obj, $color="#000") {
    print "<pre style=\"color: $color\">\n";
    print_r($obj);
    print "</pre>\n";
}

// This is an alias for debug_print()
function dp($obj, $color="#000") {
    debug_print($obj, $color);
}

// Call debug_print() and then die
function dpd($obj, $color="#000") {
    dp($obj, $color);
    die;
}

// Call dpd() with "HERE" as the argument (now I'm REALLY being lazy...)
function dpdh() {
    dpd("HERE");
}

function oops($msg = 'OOPS!')
{
    throw new Exception($msg);
}

function hasError($errors, $fieldName) {
    
    if(is_array($errors) && array_key_exists($fieldName, $errors)) {
        return true;
    }
    
}

function html_build_link($url, $target = '_blank')
{
    return '<a href="' . $url . '" target="' . $target . '">' . $url . '</a>';
}

function html_fix_url($url)
{
    $url = trim($url);
    $pos = strpos($url, '://');
    
    if ($pos === false)
    {
        $url = 'http://' . $url;
    }
    elseif ($pos == 0)
    {
        $url = 'http' . $url;
    }
    
    return $url;
}

$EMAILSUBJECT = null;

function html_activate_links($str, $emailSubject = null, $target = '_blank') {

    global $EMAILSUBJECT;

    $str = preg_replace_callback('/(([[:space:]]+(f|ht){1}tp[s]?:\/\/)[\-a-zA-Z0-9@:%_\+.~#\?\&\/=\$]+)(\|([a-zA-Z0-9\- _\.\/]+)\|)?+/i', 'external_link_string', $str);
    // FIXME: eregi_replace() is depricated. Need to use preg_replace() and updatethe reg ex.
    //$str = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2" target="' . $target .  '">\\2</a>', $str);
    
    $emailRepl = '';

    if(strlen($EMAILSUBJECT)) {
        $emailSubject = $EMAILSUBJECT;
    }

    if(! $emailSubject) {
        $emailRepl = '<a href="mailto:\\1">\\1</a>';
    } else {
        $emailRepl = "<a href=\"mailto:\\1?subject=$emailSubject\">\\1</a>";
    }
    // FIXME: eregi_replace() is depricated. Need to use preg_replace() and updatethe reg ex.
    //$str = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', $emailRepl, $str);

    return $str;

}

function html_activate_internal_links($str) {
    $matches = array();
    $str = preg_replace_callback('/(internal:\/\/)([a-zA-Z0-9@_\-\/]+)(\|([a-zA-Z0-9 \-_]+)\|)?/i', 'internal_link_string', $str);
    return $str;
}

function external_link_string($matches) {
    if(!count($matches))
    return;

    $linkText = '';

    if(count($matches) == 6) {
        $linkText = trim($matches[5]);
    } else {
        $linkText = trim($matches[1]);
    }
    $link = ' <a href="' . trim($matches[1]) . '" target="_blank">' . $linkText . '</a>';

    return $link;
}

function internal_link_string($matches) {
    return '<a href="asfunction:_root.loadMenuSection,' . $matches[2] . '">' . $matches[4] . '</a>';
}

function show_long_text($stext, $emailSubject = null) {
  //This line is only for Flash.   HTML sites don't need this one.
    $stext = preg_replace("/<\/li>[\r\n]{1,2}<li>/", "</li><li>", $stext);

  //This part gets rid of stupid MS Word "smart" characters.
    $find[] = '“';  // left side double smart quote
    $find[] = '???';  // right side double smart quote
    $find[] = '‘';  // left side single smart quote
    $find[] = '’';  // right side single smart quote
    $find[] = '…';  // elipsis
    $find[] = '—';  // em dash
    $find[] = '–';  // en dash

    $replace[] = '"';
    $replace[] = '"';
    $replace[] = "'";
    $replace[] = "'";
    $replace[] = "...";
    $replace[] = "-";
    $replace[] = "-";

    $stext = str_replace($find, $replace, $stext);

    //Activate links and convert the line breaks to <br \>
    return str_replace(array("\n", "\r"), '', nl2br(html_activate_internal_links(html_activate_links($stext))));
}

/**
 * Gets up to the first 10 words of the text, appending an
 * ellipsis if there is still more text
 */
function show_long_text_truncated($text, $wordCount = 10)
{
  if(! strlen($text))
  {
    return;
  }

  $words = preg_split('/ /', strip_tags(show_long_text($text)), -1, PREG_SPLIT_NO_EMPTY);

  $firstGroup = array_slice($words, 0, $wordCount);

  $trunc = join(' ', $firstGroup);

  if(count($words) > $wordCount)
  {
    $trunc .= '...';
  }

  return $trunc;
}

/**
 * Appends an ellipsis if the string is too long.
 */
function truncateString($text, $maxLength = 25)
{
  if(!strlen($text))
  {
    return;
  }

  if(strlen($text) > $maxLength)
  {
    $text = substr($text, 0, $maxLength) . '...';
  }

  return $text;
}

function validateEmailAddress($email)
{
    $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';    // allowed characters for part before "at" character
    $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // allowed characters for part after "at" character
    
    $regex = '^' . $atom . '+' .        // One or more atom characters.
    '(\.' . $atom . '+)*'.              // Followed by zero or more dot separated sets of one or more atom characters.
    '@'.                                // Followed by an "at" character.
    '(' . $domain . '{1,63}\.)+'.        // Followed by one or max 63 domain characters (dot separated).
    $domain . '{2,63}'.                  // Must be followed by one set consisting a period of two
    '$';

    if (eregi($regex, $email))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validatePhoneNumber($phone)
{
    if (strlen($phone) >= 10 && strlen($phone) <= 15)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 *This is mainly used in conjunction with array_map to quote strings
 *for printing as CSV output
 **/

function quotecsv($str) {
    return '"' . str_replace('"', '""', $str) . '"';
}

function stateSelectList($name, $selected, $tabindex=null) {

    $arStates = array (
             'Alabama' => 'AL',
             'Alaska' => 'AK',
             'American Samoa' => 'AS',
             'Arizona' => 'AZ',
             'Armed Forces Africa' => 'AE',
             'Armed Forces Americas' => 'AA',
             'Armed Forces Canada' => 'AE',
             'Armed Forces Europe' => 'AE',
             'Armed Forces Middle East' => 'AE',
             'Armed Forces Pacific' => 'AP',
             'Arkansas' => 'AR',
             'California' => 'CA',
             'Colorado' => 'CO',
             'Connecticut' => 'CT',
             'Delaware' => 'DE',
             'District Of Columbia' => 'DC',
             'Federated States Of Micronesia' => 'FM',
             'Florida' => 'FL',
             'Georgia' => 'GA',
             'Guam' => 'GU',
             'Hawaii' => 'HI',
             'Idaho' => 'ID',
             'Illinois' => 'IL',
             'Indiana' => 'IN',
             'Iowa' => 'IA',
             'Kansas' => 'KS',
             'Kentucky' => 'KY',
             'Louisiana' => 'LA',
             'Maine' => 'ME',
             'Marshall Islands' => 'MH',
             'Maryland' => 'MD',
             'Massachusetts' => 'MA',
             'Michigan' => 'MI',
             'Minnesota' => 'MN',
             'Mississippi' => 'MS',
             'Missouri' => 'MO',
             'Montana' => 'MT',
             'Nebraska' => 'NE',
             'Nevada' => 'NV',
             'New Hampshire' => 'NH',
             'New Jersey' => 'NJ',
             'New Mexico' => 'NM',
             'New York' => 'NY',
             'North Carolina' => 'NC',
             'North Dakota' => 'ND',
             'Northern Mariana Islands' => 'MP',
             'Ohio' => 'OH',
             'Oklahoma' => 'OK',
             'Oregon' => 'OR',
             'Palau' => 'PW',
             'Pennsylvania' => 'PA',
             'Puerto Rico' => 'PR',
             'Rhode Island' => 'RI',
             'South Carolina' => 'SC',
             'South Dakota' => 'SD',
             'Tennessee' => 'TN',
             'Texas' => 'TX',
             'Utah' => 'UT',
             'Vermont' => 'VT',
             'Virgin Islands' => 'VI',
             'Virginia' => 'VA',
             'Washington' => 'WA',
             'West Virginia' => 'WV',
             'Wisconsin' => 'WI',
             'Wyoming' => 'WY'
    );

    $out = '<select name="' . $name . '"' . (strlen($tabindex) ? " tabindex=\"$tabindex\"" : '') . '>' . "\n";
    $out .= "<option value=''>- Select State-</option>\n";

    foreach($arStates as $name => $abbrev) {

        $out .= '<option value="'. $abbrev . '"' . ($selected == $abbrev ? ' selected="selected"' : '') . '>' .
        $name .
            "</option>\n";
    }

    $out .= "</select>\n";

    return $out;

}

function monthSelectList($name, $selected, $tabindex = null) {

    $arMonths = array (
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
    );

    $out = '<select name="' . $name . '"' . (strlen($tabindex) ? " tabindex=\"$tabindex\"" : '') . '>' . "\n";
    $out .= "<option value=''>- Month -</option>\n";

    foreach($arMonths as $abbrev => $name) {

        $out .= '<option value="'. $abbrev . '"' . ($selected == $abbrev ? ' selected="selected"' : '') . '>' .
        $name .
            "</option>\n";
    }

    $out .= "</select>\n";

    return $out;

}

function daySelectList($name, $selected, $tabindex = null) {

    $out = '<select name="' . $name . '"' . (strlen($tabindex) ? " tabindex=\"$tabindex\"" : '') . '>' . "\n";
    $out .= "<option value=''>- Day -</option>\n";

    for($x=1; $x <= 31; $x++) {
        
        $out .= '<option value="'. $x . '"' . ($selected == $x ? ' selected="selected"' : '') . '>' .
        $x .
            "</option>\n";
    }

    $out .= "</select>\n";

    return $out;

}

function yearSelectList($name, $selected, $tabindex = null) {

    $out = '<select name="' . $name . '"' . (strlen($tabindex) ? " tabindex=\"$tabindex\"" : '') . '>' . "\n";
    $out .= "<option value=''>- Year -</option>\n";

  //Loop from whatever year it is currently backwards to 1900
    $thisYear = strftime('%Y', time());
    for($x=$thisYear; $x >= 1900; $x--) {
        $out .= '<option value="'. $x . '"' . ($selected == $x ? ' selected="selected"' : '') . '>' .
        $x .
            "</option>\n";
    }

    $out .= "</select>\n";

    return $out;

}

function getValueFromArray($arr, $key) {
    if (!$arr) {
        return '';
    }
    elseif (!key_exists($key, $arr)) {
        return '';
    }
    else {
        return $arr[$key];
    }
    
}

/*
function buildSelectList($name, $style, $selectedKey = -1, $arValues = array(), $arKeys = array(), $javascript = '') {

if (count($arKeys) != count($arValues)) {
//debug_print('$arValues: ' . count($arValues));
//debug_print('$arKeys: ' . count($arKeys));
die('Error: Number of keys and number of values are different!');
}

$ret = '<select name="' . $name . '" id="' . $name . '"';

if (strlen($style) > 0) {
$ret .= ' style="' . $style . '"';
}

$ret .= $javascript . '>
<option value="">- Select -</option>';

for ($i = 0; $i < count($arKeys); $i++) {
$ret .= '<option value="' . $arValues[$i] . '" ';

if ($arValues[$i] == $selectedKey) {
$ret .= ' selected="selected" ';
}

$ret .= '>' . $arKeys[$i] . '</option>';
}

$ret .= '</select>';
return $ret;
}
*/

function makePropertyArray(Array $objects, $property) {
    
    $arProps = array(count($objects));

    for($i = 0; $i < count($objects); $i++) {
        $arProps [$i] = $objects[$i]->$property;
    }
    
    return $arProps;
}

/**
 * This function prepares a string for a database.
 * Place all DB validation code here.
 * @param String $str The string to validate.
 * @return String The validated string.
 **/
function prepareStringForDB($str) {

    //$str = trim(str_replace("'", "''", $str));
    return $str;
}

/**
 * This function returns an array of string tokens contained in a string.
 * Tokens are defined as the characters between two delimiters.
 * @param string $source The string to parse
 * @param string $beginDelim The beginning delimiter
 * @param string $endDelim The ending delimiter
 * @return array()
 */
function parseTokens($source, $beginDelim, $endDelim) {
    
    $arTokenNames = array();
    $arStartPos = array();
    $arEndPos = array();
    $len = strlen($source);

    if ($len < 1) {
        return array();
    }
    
    // Get all the token start positions.
    $pos = 0;
    while ($pos < $len) {
        
        $pos = strpos($source, $beginDelim, $pos);

        if ($pos == false) {
            break;
        }
        
        array_push($arStartPos, $pos);
        $pos += strlen($beginDelim);
    }
    
    // Get all the token end positions.
    $pos = 0;
    while ($pos < $len) {
        
        $pos = strpos($source, $endDelim, $pos);

        if ($pos == false) {
            break;
        }
        
        array_push($arEndPos, $pos);
        $pos += strlen($endDelim);
    }
    
    // Alternating between start and end positions, extract the tokens.
    for ($i = 0; $i < count($arStartPos); $i++) {
        
        $tokenLength = $arEndPos[$i] - $arStartPos[$i] - strlen($beginDelim);

        $str = substr($source, $arStartPos[$i] + strlen($beginDelim), $tokenLength);

        array_push($arTokenNames, trim($str));
    }
    
    //debug_print($arTokenNames);
    //die();
    
    return $arTokenNames;
}


/**
 * Remove characters that are unsafe for windows files names.
 * @param string @str The string to modify
 * @return string The safe file name
 */
function createSafeFileName($str) {
    $str = str_replace('/', '', $str);
    $str = str_replace('\\', '', $str);
    $str = str_replace(':', '', $str);
    $str = str_replace('*', '', $str);
    $str = str_replace('?', '', $str);
    $str = str_replace('<', '', $str);
    $str = str_replace('>', '', $str);
    $str = str_replace('|', '', $str);
    $str = str_replace('&', '', $str);
    $str = str_replace('#', '', $str);
    $str = str_replace('~', '', $str);
    $str = str_replace('`', '', $str);
    $str = str_replace(' ', '_', $str);
    return trim($str);
}


function parseException(Exception $ex) {
    return $ex->getMessage();
}

function readFromFile($path)
{
    $ret = "";

    try
    {
        $fh = fopen($path, 'r') or die("can't open file");
        $ret = fread($fh, filesize($path));
        fclose($fh);
        return $ret;
    }
    catch (Exception $ex)
    {
        if ($fh != null)
        {
            fclose($fh);
            $fh = null;
        }
    }
}

function getNewDimensions($orgWidth, $orgHeight, $maxWidth, $maxHeight)
{
    // If max width and height are null/zero, just return original dimensions.
    if (!$maxWidth || !$maxHeight)
    {
        return array($orgWidth, $orgHeight);
    }

    /*
    * dims[0] = width.
    * dims[1] = height.
    */
    $dims = array(2);
    $scale = 0.0;

    // If the original smaller than or equal to the MAX, just return original.
    if ($orgWidth <= $maxWidth && $orgHeight <= $maxHeight)
    {
        $dims[0] = $orgWidth;
        $dims[1] = $orgHeight;
    }
    else
    {
        $scale = min( ($maxWidth / $orgWidth), ($maxHeight / $orgHeight) );
        $dims[0] = (int)round($scale * $orgWidth);
        $dims[1] = (int)round($scale * $orgHeight);
    }

    return $dims;
}

function isObjectPending($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $isDirty = null;
    
    if (method_exists($obj, 'getIsDirty'))
    {
        $isDirty = $obj->getIsDirty();
    }

    switch ($isDirty)
    {
        case 't':
            return true;

        case 'true':
            return true;

        case 'f':
            return false;

        case 'false':
            return false;

        default:
            throw new Exception("Unable to determine pending status of this object.");
    }
}

function displayIsObjectPending($obj)
{
    try
    {
        switch (isObjectPending($obj))
        {
            case true:
                return 'pending';

            case false:
                return 'live';

            default:
                return 'n/a';
        }
    }
    catch (Exception $ex)
    {
        return 'n/a';
    }
}

function displayIsPrimary($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $isPrimary = null;
    
    if (method_exists($obj, 'getIsPrimary'))
    {
        $isPrimary = $obj->getIsPrimary();
    }

    switch ($isPrimary)
    {
        case 1:
            return 'yes';
        
        case 0:
            return 'no';
        
        default:
            return 'n/a';
    }
}

function displayIsFeatured($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $isFeatured = null;
    
    /*
     * If this is a deliverable with a parent Item, 
     * do not display isFeatured.
     */
    if (method_exists($obj, 'getProgramId') && $obj->getProgramId() > 0)
    {
        return '(Deliverables within a Program or Case Study cannot be featured directly.)';
    }
    
    if (method_exists($obj, 'getIsFeatured'))
    {
        $isFeatured = $obj->getIsFeatured();
    }

    switch (toBoolean($isFeatured))
    {
        case true:
            return 'yes';
        
        case false:
            return 'no';
        
        default:
            return 'n/a';
    }
}

function displayObjectEnabled($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $isEnabled = null;
    
    if (method_exists($obj, 'getIsEnabled'))
    {
        $isEnabled = $obj->getIsEnabled();
    }

    switch (toBoolean($isEnabled))
    {
        case true:
            return 'yes';
        
        case false:
            return 'no';
        
        default:
            return 'n/a';
    }
}

function displayObjectRequired($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $isRequired = null;
    
    if (method_exists($obj, 'getIsRequired'))
    {
        $isRequired = $obj->getIsRequired();
    }

    switch (toBoolean($isRequired))
    {
        case true:
            return 'yes';
        
        case false:
            return 'no';
        
        default:
            return 'n/a';
    }
}

function displayObjectHasHyperlink($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $hasHyperlink = null;
    
    if (method_exists($obj, 'getHasHyperlink'))
    {
        $hasHyperlink = $obj->getHasHyperlink();
    }

    switch (toBoolean($hasHyperlink))
    {
        case true:
            return 'yes';

        case false:
            return 'no';

        default:
            return 'n/a';
    }
}

function displayOptionalIntValue($value)
{
    if ($value > 0)
    {
        return $value;
    }
    else
    {
        return 'n/a';
    }
}

function displayOptionalStringValue($value)
{
    if (strlen($value) > 0)
    {
        return $value;
    }
    else
    {
        return 'n/a';
    }
}

function isObjectActive($obj)
{
    if (!isset($obj)) throw new Exception("Object is null!");
    
    $isEnabled = null;
    $startDate = null;
    $endDate = null;
    
    if (method_exists($obj, 'getIsEnabled'))
    {
        if ($obj->getIsEnabled() == 'true'
            || $obj->getIsEnabled() == 't')
        {
            $isEnabled = true;
        }
        else
        {
            $isEnabled = false;
        }
    }
    
    if (method_exists($obj, 'getStartDate'))
    {
        $startDate = $obj->getStartDate();
    }
    
    if (method_exists($obj, 'getEndDate'))
    {
        $endDate = $obj->getEndDate();
    }
    
    return isActive($isEnabled, $startDate, $endDate);
}

/**
 * Determines the "active" status of an item based on isEnabled, startDate
 * and endDate.
 */
function isActive($isEnabled = true, $startDate = null, $endDate = null)
{
    if ((isset($isEnabled) && !$isEnabled)
        || (isset($startDate) && strtotime($startDate) > time())
        || (isset($endDate) && strtotime($endDate) < time()))
        {
            return 'no';
        }
        else
        {
            return 'yes';
        }
}

function is_even($number)
{
    return ($number % 2) ? false : true;
}

function is_odd($number)
{
    return ($number % 2) ? true : false;
}

function getArrayValue($key, $array = array())
{
    if (isset($array) && strlen($key) && array_key_exists($key, $array) && strlen(trim($array[$key])))
    {
        return trim($array[$key]);
    }
    else
    {
        return null;
    }
}

function getRequiredArrayValue($key, $array = array())
{
    $tmp = getArrayValue($key, $array);

    if ($tmp)
    {
        return $tmp;
    }
    else
    {
        throw new Exception('Invalid property "' . $key . '"!');
    }
}

function toBoolean($val)
{
    if (is_bool($val))
    {
        return $val;
    }
    elseif (strtolower($val) == 'true' || strtolower($val) == 't' || (is_numeric($val) && $val == 1))
    {
        return true;
    }
    elseif (strtolower($val) == 'false' || strtolower($val) == 'f' || (is_numeric($val) && $val == 0))
    {
        return false;
    }
    else
    {
        throw new Exception('Unable to convert value to boolean: ' . $val);
    }
}

function test_toBoolean()
{
    $cases = array(
        true,
        false,
        1,
        0,
        '1',
        '0',
        'true',
        'false',
        'TRUE',
        'FALSE',
        'True',
        'False',
        'tRUe',
        'f',
        't',
        null,
        'fail'
    );

    foreach ($cases as $case)
    {
        try
        {
            echo 'toBoolean(' . $case . ') = ' . toBoolean($case) . PHP_EOL;
        }
        catch (Exception $ex)
        {
            echo 'toBoolean(' . $case . ') threw an Exception: ' . $ex->getMessage() . PHP_EOL;
        }
    }    
}


/**
* This function turns PHP core notices and warnings (aka recoverable errors) 
* into exceptions. To use it, include this line in the consuming code:
* 
*     set_error_handler('handleErrorAsException');
* 
* From: http://www.alexatnet.com/node/23
* 
* @param mixed $errno
* @param mixed $errstr
* @param mixed $errfile
* @param mixed $errline
*/
function handleErrorAsException($errno, $errstr, $errfile, $errline)
{
    throw new Exception($errstr, $errno);
}

function simpleXmlAttributeToString($object, $attribute)
{
    if(isset($object[$attribute]))
        return (string) $object[$attribute];
}

function getQueryStringParamFromUrl($url, $param)
{
    $urlParams = array();
    parse_str(parse_url($url, PHP_URL_QUERY), $urlParams);
    return getArrayValue($param, $urlParams);
}

/*function parseBoolean($val)
{
    if (strtolower($val) == 'true'
        || strtolower($val) == 't'
        || $val == 1)
    {
        return 1;
    }
    elseif (!strlen($val))
    {
        return -1;
    }
    elseif (strtolower($val) == 'false'
        || strtolower($val) == 'f'
        || $val == 0)
    {
        return 0;
    }
    else
    {
        return -1;
    }
}*/

function parseBoolean($val)
{
    if (strtolower($val) == 'true'
        || strtolower($val) == 't'
        || $val == 1)
    {
        return true;
    }
    elseif (strtolower($val) == 'false'
        || strtolower($val) == 'f'
        || $val == 0)
    {
        return false;
    }
    else
    {
        throw new Exception("Invalid boolean value: " . $val);
    }
}

/**
* This is a wrapper function to accommodate array_map(). It works with any class that defines an "asArray()" method.
* 
* @param $obj
*/
function convertObjectToArray($obj)
{
    if (!method_exists($obj, 'asArray'))    // Note: I would have used an interface but I'm currently building for CodeIgniter, which doesn't support interfaces OOTB.
        throw new Exception("Cannot convert to array. No asArray() method exists for this object type.");
    
    return $obj->asArray();
}

function trimStringArray(array $values)
{
    if (!count($values))
        return array();

    $fixedValues = array();

    foreach ($values as $value)
        if (strlen(trim($value)))
                    array_push($fixedValues, trim($value));
    
    return $fixedValues;
}

function getRequestValue($key, $default = null)
{
	return array_key_exists($key, $_REQUEST) ? $_REQUEST[$key] : $default;
}

/**
 * Returns the url query as associative array
 *
 * @param    string    query
 * @return    array    params
 */
function getUrlQueryAsArray($url)
{
	$parsedUrl = parse_url($url);
	$query = $parsedUrl['query'];
    $queryParts = explode('&', $query);
    $params = array();
    
    foreach ($queryParts as $param)
    {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
   
    return $params;
}



/**
 * Send a POST requst using cURL
 * Source: http://www.php.net/manual/en/function.curl-exec.php
 * @param string $url to request
 * @param array $post values to send
 * @param array $options for cURL
 * @return string
 */
function curl_post($url, array $post = NULL, array $options = array())
{
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => http_build_query($post)
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

/**
 * Send a GET requst using cURL
 * Source: http://www.php.net/manual/en/function.curl-exec.php
 * @param string $url to request
 * @param array $get values to send
 * @param array $options for cURL
 * @return string
 */
function curl_get($url, array $get = NULL, array $options = array())
{   
    $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4
    );
   
    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
?>