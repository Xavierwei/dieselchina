<?php

/**
 * Ritorna il valore della variabile d'ambiente "HART_APP_CONTEXT".
 * Se nullo ritorna stringa vuota
 */
function proxy_get_appcontext(){
	$appContext = getenv('HART_APP_CONTEXT');
	if( $appContext == null ){
		$appContext == '';
	}
	return $appContext;
}


/**
 * Returns a routed URL based on the module/action passed as argument
 * and the routing configuration.
 *
 * <b>Examples:</b>
 * <code>
 *  echo url_for('my_module/my_action');
 *    => /path/to/my/action
 *  echo url_for('@my_rule');
 *    => /path/to/my/action 
 *  echo url_for('@my_rule', true);
 *    => http://myapp.example.com/path/to/my/action
 * </code>
 *
 * @param  string $internal_uri  'module/action' or '@rule' of the action
 * @param  bool   $absolute      return absolute path?
 * @return string routed URL
 */
function proxy_url_for()
{
	$appContext = proxy_get_appcontext();
	if( $appContext != "" ){
		$appContext = "/" . $appContext;
	}

	$arguments = func_get_args();

	return $appContext . call_user_func_array('url_for', $arguments);
}
