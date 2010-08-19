<?php

class PrototypePage extends Page
{
	static $db = array();
	
}

class PrototypePage_Controller extends Page_Controller
{
	public function init() {
		parent::init();
		
		
		// jQuery UI
		Requirements::javascript("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js");
		
		// Generic CSS
		
		Requirements::css('sprocketPrototype/css/prototype-general.css');
		
		
		
		// YouTube Test Stuff
		
		Requirements::javascript('sprocketPrototype/js/youtube-player/jquery.swfobject.1-1-1.min.js');
		Requirements::javascript('sprocketPrototype/js/youtube-player/youTubeEmbed/youTubeEmbed-jquery-1.0.js');
		//Requirements::javascript('sprocketPrototype/youtube-player/youTubeEmbed/youTubeThumb-jquery-0.1.js');
		Requirements::javascript('sprocketPrototype/js/youtube-player/script.js');

		Requirements::css('sprocketPrototype/js/youtube-player/youTubeEmbed/youTubeEmbed-jquery-1.0.css');
		Requirements::css('sprocketPrototype/css/youtube.css');
		
		
		// Drag Drop Lock Test Stuff
		
		Requirements::javascript('sprocketPrototype/js/drag-drop-lock/cart.js');
		
		Requirements::css('sprocketPrototype/css/drag-drop-lock.css');
		
		
		
		
	}
}