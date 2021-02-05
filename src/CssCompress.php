<?php
namespace Herokeyboard;
class CssCompress
{
    public static function compress($type, $cssFiles = array())
    {

        $response = self::requestCssCompress($type, $cssFiles);
        return $response;
    }

    protected static function requestCssCompress($type, $cssFiles)
    {  
		$buffer = "";
		foreach ($cssFiles as $cssFile) {
		  $buffer .= @file_get_contents($cssFile);
		}
		if($type !== 'false') 
		{
			if (trim($buffer) === "")
		    {
		   	 return $buffer;
		    } 

		    if (strpos($buffer, 'calc(') !== false) {
		        $buffer = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function($matches) {
		            return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
		        }, $buffer);
		    }
		    if (strpos($buffer, '../images') !== false) {
		        $buffer =  str_replace('../images','images',$buffer);
		    }

		    $a =['#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
		    	'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
		        '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
		        '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
		        '#(background-position):0(?=[;\}])#si',
		        '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
		        '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
		        '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
		        '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
		        '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
		        '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
		        '#\x1A#'];
		    $b =['$1',
		        '$1$2$3$4$5$6$7',
		        '$1',
		        ':0',
		        '$1:0 0',
		        '.$1',
		        '$1$3',
		        '$1$2$4$5',
		        '$1$2$3',
		        '$1:0',
		        '$1$2',
		        ' '];
		    $buffer = @preg_replace($a,$b,$buffer);
		    ob_start("ob_gzhandler");
		    header('Cache-Control: public');
		    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
		    header("Content-type: text/css");
		    return "<style>".$buffer."</style>";
		}else {

			$filecss = "";
			foreach ($cssFiles as $cssFile) 
			{
			  $filecss .= '<link rel="stylesheet" href="'.$cssFile.'">';
			}
		    return $filecss;
		}


    }

}

?>
