<?php

/**
 * Enigma 2.0
 * @version 1.0
 * @author Kyle Hardgrave <kyle@kylehardgrave.com>
 */

//error_reporting(E_ALL);

/*- The Arrays
-----------------------------------------------------------------------------*/
$errors = array();
$upper_alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$lower_alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');





/*- The Caesar Shift
-----------------------------------------------------------------------------*/
function caesar($input_array, $key, $op) {
  global $upper_alphabet, $lower_alphabet;
  $output = '';
  if (preg_match('/[0-9]{1,2}/', $key)) {
    $k = intval($key);
  } else {
    return 'bad_key';
  }
  if ($k > 26 or $k < 0) return 'bad_key';
  foreach ($input_array as $input_letter) {
    if (preg_match('/[A-Z]/', $input_letter)) {
      for ($i = 0; $i < 26; $i++) {
        if ($upper_alphabet[$i] == $input_letter) {
          if ($op == 'encrypt') {
            $output .= ($i > (25 - $k)) ? 
	      $upper_alphabet[$i + $k - 26] : $upper_alphabet[$i + $k];
          } elseif ($op == 'decrypt') {
            $output .= ($i < $k) ? 
	      $upper_alphabet[$i +26 - $k] : $upper_alphabet[$i - $k];
          } else {
            return 'bad_op';
          }
        }
      }
    } elseif (preg_match('/[a-z]/', $input_letter)) {
      for ($i = 0; $i < 26; $i++) {
        if ($lower_alphabet[$i] == $input_letter) {
          if ($op == 'encrypt') {
            $output .= ($i > (25 - $k)) ? 
	      $lower_alphabet[$i + $k - 26] : $lower_alphabet[$i + $k];
          } elseif ($op == 'decrypt') {
            $output .= ($i < $k) ? 
	      $lower_alphabet[$i + 26 - $k] : $lower_alphabet[$i - $k];
          } else {
            return 'bad_op';
          }
        }
      }
    } else {
      $output .= $input_letter;
    }
  }
  return $output;
}





/*- The Atbash Cipher
-----------------------------------------------------------------------------*/
function atbash($input_array) {
  global $upper_alphabet, $lower_alphabet;
  $output = '';
  foreach ($input_array as $input_letter) {
    if (preg_match('/[A-Z]/', $input_letter)) {
      for ($i = 0; $i < 26; $i++) {
        if ($upper_alphabet[$i] == $input_letter) 
	  $output .= $upper_alphabet[25 - $i];
      }
    } elseif (preg_match('/[a-z]/', $input_letter)) {
      for ($i = 0; $i < 26; $i++) {
        if ($lower_alphabet[$i] == $input_letter) 
	  $output .= $lower_alphabet[25 - $i];
      }
    } else {
      $output .= $input_letter;
    }
  }
  return $output;
}





/*- The Obscure Function
-----------------------------------------------------------------------------*/
function obscure($str) {
  $output = '';
  $str_array = str_split(strtoupper($str));
  foreach ($str_array as $char) {
    if (preg_match('/[A-Z]/', $char)) 
      $final_array[] = $char;
  }
  for ($i = 0; $i < count($final_array); $i++) {
    if (($i + 1) % 5 == 0) 
      $final_array[$i] .= ' ';
    $output .= $final_array[$i];
  }
  return $output;
}





/*- Just Do It
-----------------------------------------------------------------------------*/
if ($_POST['submit-form']) {
  $submitted = true;
  if (!$_POST['field101']) { $errors[] = 'no_op'; } else { $operation = $_POST['field101']; }
  if (!$_POST['field102']) { $errors[] = 'no_scheme'; } else { $scheme = $_POST['field102']; }
  if (!$_POST['field104']) { $errors[] = 'no_input'; } else { $input_array = str_split($_POST['field104']); }
  $key = $_POST['field103'];
  $obscure = $_POST['field105'];
  if (!$errors) {
    if ($scheme == 'rot') {
      $output = caesar($input_array,$key,$operation);
    } elseif ($scheme == 'atbash') {
      $output = atbash($input_array);
    }
    if ($output == 'bad_key') {
      $errors[]= 'bad_key';
      $output = '';
    }
    if ($obscure) $output = obscure($output);
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<title>Enigma 2.0</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="robots" content="index, follow" />

<style type="text/css">
@import url(style.css);
</style>
<!--[if lte IE 7]>
<style type="text/css">
#enigma2 label.label { display: block; position: static; }
#enigma2 li .field { margin-left: 0; }
#header h1 { font-style: normal; line-height: 0.7; }
#footer a { color: #888; }
</style>
<![endif]-->
<!--<script type="text/javascript" src="wufoo-scripts.js"></script>-->
</head>





<body>

<div id="main">
  <img id="top" src="top.png" alt="" />
  <div id="header">
    <h1>Enigma 2.0</h1>
    <p>or, <cite>Kyle&rsquo;s Fantastic Form that Encrypts Things</cite>.</p>
  </div>
  <div id="content">
    <div class="info">
      <h2>This is how we do</h2>
      <p>Use this handy form to encrypt things. At the moment, we only have two basic types of <a href="http://en.wikipedia.org/wiki/Substitution_cipher">substitution cipher</a>: the <a href="http://en.wikipedia.org/wiki/Caesar_cipher">Caesar shit</a> and  <a href="http://en.wikipedia.org/wiki/Atbash">Atbash</a>.  If you select the Caesar shift, then you must input a key (i.e. the shift amount) between 0 and 26. The Atbash cipher requires no key.</p>
      <p><strong>Note:</strong> The ciphers created by this website are mostly archaic, and should not be considered reliable in real-world scenarios.</p>
    </div>
    <form id="enigma2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <ul>
        <?php if ($errors) { ?><li id="error-msg">
          <h3><acronym title="Omigod!">OMG!</acronym> A problem occurred with your submission!</h3>
          <p>Errors have been <strong>highlighted</strong> below.</p>
        </li><?php } ?>
        <li id="operation-field" <?php if (in_array('no_op',$errors)) { ?>class="error"<?php } ?>>
          <label class="label">Operation <span>*</span></label>
          <div class="field">
            <div>
              <input id="field101-a" name="field101" type="radio" tabindex="1" value="encrypt"<?php if((!$submitted) or ($operation=='encrypt')) { ?> checked="checked"<?php } ?> />
              <label for="field101-a">Encrypt (plaintext<span class="arrow">&rarr;</span>ciphertext)</label>
            </div>
            <div>
              <input id="field101-b" name="field101" type="radio" tabindex="2" value="decrypt"<?php if($operation=='decrypt') { ?> checked="checked"<?php } ?> />
              <label for="field101-b">Decrypt (ciphertext<span class="arrow">&rarr;</span>plaintext)</label>
            </div>
            <?php if (in_array('no_op',$errors)) { ?><p class="error">This field is required. Please select an option.</p><?php } ?>
          </div>
        </li>
        <li id="scheme-field" <?php if (in_array('no_scheme',$errors)) { ?>class="error"<?php } ?>>
          <label class="label" for="field102">Encryption Scheme <span>*</span></label>
          <div class="field">
            <select id="field102" name="field102" tabindex="3">
              <option value=""<?php if(!$submitted) { ?> selected="selected"<?php } ?>></option>
              <option value="rot"<?php if($scheme=='rot') { ?> selected="selected"<?php } ?>>Caesar</option>
              <option value="atbash"<?php if($scheme=='atbash') { ?> selected="selected"<?php } ?>>Atbash</option>
            </select>
            <?php if (in_array('no_scheme',$errors)) { ?><p class="error">This field is required. Please select an option.</p><?php } ?>
          </div>
        </li>
        <li id="key-field" <?php if (in_array('bad_key',$errors)) { ?>class="error"<?php } ?>>
          <label class="label" for="field103">Key</label>
          <div class="field">
            <input id="field103" name="field103"  type="text" tabindex="4" maxlength="255" value="<?php echo $key; ?>" />
            <?php if (in_array('bad_key',$errors)) { ?><p class="error">Please provide a valid encryption key.</p><?php } ?>
          </div>
        </li>
        <li id="obscure-field">
          <div class="field">
            <input id="field105" name="field105" type="checkbox" tabindex="5" value="obscure" />
            <label for="field105">Obscure ciphertext</label>
          </div>
        </li>
        <li id="text-field" <?php if (in_array('no_input',$errors)) { ?>class="error"<?php } ?>>
          <label class="label" for="field104">Input/Output <span>*</span></label>
          <div class="field">
            <textarea id="field104" name="field104" rows="10" cols="50" tabindex="6"><?php echo $output; ?></textarea>
            <?php if (in_array('no_input',$errors)) { ?><p class="error">This field is required. Please provide a value.</p><?php } ?>
          </div>
        </li>
        <li id="submit-field">
          <div class="field"><input name="submit-form" id="submit-form" type="submit" tabindex="7" value="Submit" /></div>
        </li>
      </ul>
    </form>
  </div>
  <img id="bottom" src="bottom.png" alt="" />
  <p id="footer">Copyright &copy; 2007&ndash;2008 by <a href="http://kylehardgrave.com/">Kyle Hardgrave</a>. <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/" title="Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License">Some rights reserved</a>. Brought to you in part by <a href="http://wufoo.com/" title="A handy form creation service.">Wufoo</a>.</p>
</div>

</body>
</html>