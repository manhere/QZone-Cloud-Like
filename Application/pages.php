<?php
class Page
{
    static function LoadContent($file) {
		include(dirname(__FILE__)."/../".TEMPLATE."/header.phtml");
        $filepath = dirname(__FILE__)."/../".TEMPLATE."/".$file;
		if(file_exists($filepath)){
			include($filepath);
		}else{
			include(dirname(__FILE__)."/../".TEMPLATE."/404.html");
		}
		include(dirname(__FILE__)."/../".TEMPLATE."/footer.phtml");
    }
}

?>