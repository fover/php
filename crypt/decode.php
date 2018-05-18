<?php
namespace encrypt;
$sts=microtime(true);
ini_set('display_errors','on');
error_reporting(E_ALL);
include_once('rsa.php');
include_once('aes.php');

$data='ZyL/vzQLoUrIx2feVT22hfaI0XnsRvYubXkrO3X0FkQnjX0mBMfsGJyzu4dxFDQHm/EEZSD3Z3pxwTRvd+WzckXGp1TLyz76s3CTZWxYX4Lnl2JL1Ev/+4Gs2sS3mARb4B2SnKJAmKSW6yEOYTTfJN3B3odQVooetwuO/hkkHVXkWjsZQxOn2x5ZhcK9/0es';

$code='Qepm86LfZmC0GYqPiuxkDKqs29ATI5GvcfNA8KheL61EhEski0yT69sOp+5etn+oVz7suXr4aieLCkp0i1uW74mCAljB2XOr+gPe8K4Y1YikRbV5hWJpNIxavFE16d2sxrEz50eVOIlNWFlTxagckE+EkwMVRPTN5+XU9jPYL7Y=';


$private_key='-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQC51et6onwjnZ2pRrTTEzANcpE/6YWi0qPFgLZWHOFMIBpmLXy/
52vEsohVQHsmcEU/50vLuMTWU/CU/NG3IV9OW0RdmbHcr/1uEte+eEd0YfVcpcbe
8liFOevWWQVj9jvmraRi68jXtuWXFVTRM0/kjI4u3HA+dIHij39NScK1UwIDAQAB
AoGAfwgDGuZ9U19HydHig7LHEzowne7EggPZHYYZng3J2F7NjPElKI1KNsAPv67/
P8xZGhDC2DSqoRPqDf4wYS231k0X2YolJpcJ+Xw30C9aeUH5D/hsX8xhzM+nV3yy
yvUIQpUBT5FGtVulBuwy6LNbWeHQ2FkXDK0YmMXEQak4U+ECQQD0knUHmmVMfLax
GxYMyxNSRt/Ac/4Tm98qpb9OGnu6RZNZROkqAdpZ3iTFlKSG8qNPkMo7nI0LpqnM
SolMAlURAkEAwoTdJchOXSOxseFALTTXtnjY1QwQalYrxjs1fpk9HfuwFZy2tZBj
/IzqMtkfzBWuMapYNg/w/65V9JQXSw/UIwJBAKbn0DAZIOp67d6dwoWGjTAIKCjZ
v9o39KvRI2Y00p1DYBR637iIPTA5VtTz5PgnXGYvRKQ76VG7MoO1lk8mBFECQQCp
TJpt1/jcd8Sg3TvOHL/iwSt3whhHdNiEn+PfW+AlyHlpVgxv2kwr8zmjJ/bU2cnS
0EAWTamj30hQptPfMNehAkEAu2Ob+LjVE+iS+MKDLWVrBaumu1uctLVXzXSSv6Sw
zdOfB8dWsc4e3bXdg+DcozJX7aNQIql93+CCM/pJiDaoJA==
-----END RSA PRIVATE KEY-----';


class Decode{
	static private $private_key;
	static public $instance;
	
	public static function Set($private_key){
		self::$private_key=$private_key;
		if(!self::$instance) self::$instance = new self();
		
        return self::$instance;
	}
	
	
	public function Run($data,$code){
		$RSA = new RSA('',self::$private_key);
		$key_str=$RSA->decrypt($code);  
		if (empty($key_str)) {
			throw new \Exception('Aes 密钥错误');
			return null;
		}
		
		$AES = new CryptAES();
	
		$AES->set_key($key_str);
		$AES->set_iv($key_str);
		$AES->require_pkcs5();
		$main_data=$AES->decrypt($data);
		if (empty($main_data)) {
			throw new \Exception('body 解密错误');
			return null;
		}
		
		return $main_data;
	}
}

echo $st = Decode::Set($private_key)->Run($data,$code);
echo "\n";
parse_str($st,$parr);
print_r($parr);
echo microtime(true)-$sts;