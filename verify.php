<?php
  // Your Paddle 'Public Key'
  $public_key_string = '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAncWOfnvXciow60nwb7te
uwbluhc2WLdy8C3E4yf+gQEGjR+EXwDogWAmpJW0V3cRGhe41BBtO0vX39YeEjh3
tkCIT4JTkR4yCXiXJ/tYGvsCAwEAAQ==
-----END PUBLIC KEY-----';

  $public_key = openssl_get_publickey($public_key_string);  //this line will fail.  The variable $public_key is always null
  
  // Get the p_signature parameter & base64 decode it.
  $signature = base64_decode($_POST['p_signature']);
  
  // Get the fields sent in the request, and remove the p_signature parameter
  $fields = $_POST;
  unset($fields['p_signature']);
  
  // ksort() and serialize the fields
  ksort($fields);
  foreach($fields as $k => $v) {
	  if(!in_array(gettype($v), array('object', 'array'))) {
		  $fields[$k] = "$v";
	  }
  }
  $data = serialize($fields);
  
  // Verify the signature
  $verification = openssl_verify($data, $signature, $public_key, OPENSSL_ALGO_SHA1);
  
  if($verification == 1) {
	  echo 'Yay! Signature is valid!';
  } else {
	  echo 'The signature is invalid!';
  }
?>
