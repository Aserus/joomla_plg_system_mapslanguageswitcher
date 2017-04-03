<?php
defined('_JEXEC') or die;

class plgSystemMapsLanguageSwitcher extends JPlugin
{

	protected $autoloadLanguage = true;

	public function onBeforeCompileHead()
	{
		$app    = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		$replaceLinks=array();
		
		foreach($doc->_scripts as $link=>$scr){
			if($this->_checkLink($link)){
				$replaceLinks[]=$link;
			}
			
		}
		
		foreach($replaceLinks as $oldLink){
			$newLink=$this->_setLanguage($oldLink);
			$this->_replacekey($doc->_scripts,$oldLink,$newLink);			
		}		
	}
	
	private function _replacekey(&$array,$currentkey,$newkey) 
	{ 
		$k  = array_keys($array); 
		$cpos = array_search($currentkey,$k); 
		$array = array_merge(array_slice($array,0,$cpos),array($newkey => $array[$currentkey]),array_slice($array,$cpos+1)); 
	} 
	
	private function _checkLink($link){
		if ($this->_checkLinkYandex($link)) return true;			
		if ($this->_checkLinkGoogle($link)) return true;			
		return false;
	}
	
	private function _checkLinkYandex($link){
		$pos = strpos($link, '//api-maps.yandex.ru/');
		if ($pos!== false) {
			return true;
		}			
		return false;
	}
	private function _checkLinkGoogle($link){				
		$pos = strpos($link, '//maps.googleapis.com/maps/api');
		if ($pos!== false) {
			return true;
		}			
		$pos = strpos($link, '//maps.google.com/maps/api');
		if ($pos!== false) {
			return true;
		}			
		return false;
	}
	
	
	private function _setLanguage($link){		
		if($this->_checkLinkGoogle($link)){			
			return $this->_setLanguageGoogle($link);
		}elseif($this->_checkLinkYandex($link)){			
			return $this->_setLanguageYandex($link);
		}		
		return false;
	}
	
	private function _setLanguageYandex($link){	
		$localParam='lang';
		$link=$this->_clearLinkParams($localParam,$link);
		return $link.'&'.$localParam.'='.$this->params->get( 'lang_yandex', 'en_US' );
	}
	private function _setLanguageGoogle($link){		
		$localParam='language';
		$link=$this->_clearLinkParams($localParam,$link);
		return $link.'&'.$localParam.'='.$this->params->get( 'lang_google', 'en' );
	}
	
	private function _clearLinkParams($param,$link){		
		$linkParams=explode('&',$link);

		if(count($linkParams)<2) return $link;
		$newLink=array();
		
		foreach($linkParams as $item){
			$p=explode('=',$item);
			if(count($p)==1){
				$newLink[]=$item;
			}elseif(count($p)>1){
				if($p[0]!=$param){
					$newLink[]=$item;
				}
			}
		}
		
		return implode('&',$newLink);
	}
	
/*
	public function onAfterRender()
	{
		
		
	}
	*/
}