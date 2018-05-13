<?php

if( ! function_exists('build_sort_link') ) {
    /**
     * Build sort link for sort
     * @param $sortKey
     * @param $link
     */
    function build_sort_link($sortKey, $link) {
    	if(!filter_var($link, FILTER_VALIDATE_URL)) {
    		throw new Exception($link. " is not valid url", 1);
    	}

        // Parse url
        $parseUrl = parse_url($link);
        if(!isset($parseUrl['query'])) {
            $queryParams = [];
        } else {
            parse_str($parseUrl['query'], $queryParams);
        }

        // Attach action
        $queryParams['_action'] = 'sort';
        $queryParams['sort_key'] = $sortKey;

        if(!isset($parseUrl['port'])) $parseUrl['port'] = 80;

        // Domain url
        if(80 !== (int) $parseUrl['port']) {
            $url = $parseUrl['scheme'] . '://' . $parseUrl['host'] . ':' . $parseUrl['port'] . $parseUrl['path'];
        } else {
            $url = $parseUrl['scheme'] . '://' . $parseUrl['host'] . $parseUrl['path'];
        }

        // Default sort
        if(!isset($queryParams['sort_value'])) {
            $queryParams['sort_value'] = "DESC";
        }

        // To lower
        foreach($queryParams as $key => $value) {
            $queryParams[$key] = strtolower($value);
        }

        // Switch sort value
        if ($queryParams['sort_value'] == "asc") {
            $queryParams['sort_value'] = "desc";
        } else {
            $queryParams['sort_value'] = "asc";
        }


        return $url . '?' . http_build_query($queryParams);
    }
}


if( ! function_exists('get_icon_sort') ) {

	/**
	 * Get icon sort
	 * @param  string $key
	 * @param  array  $query
	 * @return string
	 */
	function get_icon_sort($key, array $query)
	{
		$action = array_get($query, '_action');
		$sortKey = array_get($query, 'sort_key');
		$sortValue = strtolower(array_get($query, 'sort_value'));
		if($action == 'sort' && $sortKey == $key) {
			if($sortValue == 'asc') {
				return '<i class="fa fa-caret-down"></i>';
			}else{
				return '<i class="fa fa-caret-up"></i>';
			}
		}
	}
}


if( ! function_exists('get_sort_link') ) {
	/**
	 * Get sort link
	 * @param  string $key
	 * @param  string $link
	 * @param  array  $query
	 * @return string
	 */
	function get_sort_link($label, $key, $link, array $query) {
		return sprintf('<a href="'.build_sort_link($key, $link).'">%s %s</a>', $label, get_icon_sort($key, $query));
	}
}