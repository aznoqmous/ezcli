# ezcmd
 Single file drag and drop CLI !

## Installation
1. Upload the `ezcli.php` file on a server, in an accessible directory  
_eg: `/home/foo/bar/ezcmd.php` accessible over https://foo.bar/ezcmd.php_
2. Replace the first line `TOKEN` by a md5 value (https://www.md5hashgenerator.com/)  
_eg: `const TOKEN = "f20802a1753539b899b83b0438299447"`_
3. Access the file directly with your token in GET parameter, using pre md5 token
_eg: https://foo.bar/ezcmd.php?token=azd123
4. Enjoy !
