<?php
	
	include_once($HOME."/site/includes/retail/retail_basket_cookie_functions.php");
	
	//	Check progression through order process
	$priority_level['/site/scripts/retal_basket.php'] = 0;
	$priority_level['/site/scripts/retail_order_delivery_destination.php'] = 1;
	$priority_level['/site/scripts/retail_order_delivery_options.php'] = 2;
	$priority_level['/site/scripts/retail_order_review.php'] = 3;
	$priority_level['/site/scripts/retail_order_payment.php'] = 4;
	$priority_level['/site/scripts/retail_order_complete.php'] = 5;
	
	$currentPage = $_SERVER['PHP_SELF'];

	$basket = new Basket();

	//	Check for zero basket contents
	if ($currentPage != '/site/scripts/retail_basket.php') {
		if (sizeof($basket->items) < 1) {
			header('Location: http://'.$DOMAIN.'/site/scripts/retail_basket.php');
			exit;
		}
		unset($basket);
	}
	
	if (isset($_SESSION['stage'])) {
		print($priority_level[$_SESSION['stage']] );
		print($priority_level[$currentPage]-1);
		print($_SESSION['HTTP_REFERER']);
		
		if ($priority_level[$_SESSION['stage']] >= ($priority_level[$currentPage]-1)) {
			
			//if we are progressing and not going back we have to check that we're comming from
			//the previous stage and havent just hit the next url in the browser
			if ($priority_level[$_SESSION['stage']] == ($priority_level[$currentPage]-1)) {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$stageAddress = explode('?','http://'.$DOMAIN.$_SESSION['stage']);
					$refererAddress = explode('?',$_SERVER['HTTP_REFERER']);
					if ($refererAddress[0] == $stageAddress[0]) {
						//allow to progress and unset the order complete restraint
						unset($_SESSION['order_complete']);
					} else {
						//back to last stage
						header('Location:'.$_SESSION['stage']);
						exit();
					}
				} else {
					//user has just hit the URL in the address bar, send them back to previous stage
					header('Location:'.$_SESSION['stage']);
					exit();
				}
			}			
			$_SESSION['stage'] = $_SERVER['PHP_SELF'];
		} else {
			header('Location:'.$_SESSION['stage']);
			exit();
		}
	} else {
		$_SESSION['stage'] = '/site/scripts/retail_basket.php';
		//header('Location:basket.php');
		//exit();
	}	
	//	END	Check progression through order process
	
?>