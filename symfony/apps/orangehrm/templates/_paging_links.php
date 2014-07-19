<?php
/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */



if(isset($params)) {	
	foreach( $params as $parameterSet ) {
		 $parametrString .= $parameterSet;
	}   
} else {
	$parametrString = '';
}

echo link_to_unless($pager->getPage() == 1, __('First') . " ", $url, array('query_string' => 'page=1'.$parametrString));
echo link_to_unless($pager->getPreviousPage() == $pager->getPage(), 
	__('Previous'), $url, array('query_string' => 'page=' . $pager->getPreviousPage() .$parametrString ));  

foreach ($pager->getLinks() as $page):
	echo link_to_unless($page == $pager->getPage(), $page, $url, array('query_string' => 'page=' . $page.$parametrString));
endforeach;

echo link_to_unless($pager->getNextPage() == $pager->getPage(), 
	__('Next'), $url, array('query_string' => 'page=' . $pager->getNextPage() .$parametrString ));
echo link_to_unless($pager->getLastPage() == $pager->getPage(), 
	__('Last'), $url, array('query_string' => 'page=' . $pager->getLastPage() .$parametrString ));
