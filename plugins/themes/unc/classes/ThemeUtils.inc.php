<?php
class ThemeUtils {
	static function stripAccents($str)
	{
		return strtr(utf8_decode($str),
				utf8_decode('ÁÉÍÓÚáéíóúü'),
				'AEIOUaeiouu');
	}		

}
