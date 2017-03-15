<?php
/*
 Template Name: Ticket Picker Page
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

<style>
#inner-content {
	margin-top: 135px;
}
.footer-columns li {
	text-align: left;
}
#tn-maps {
	width: 100%;
}
footer.footer{
	background-color: transparent;
}
.container {
	width: 100%;
}
#venue-map img {
	width: auto;
}
</style>

			<div id="content" class="choose-ticket">

				<div id="inner-content" class="wrap cf">

					<?php
						$eventID = $_GET['eventID'];
					?>

					<script type="text/javascript" src="//d3cxv97fi8q177.cloudfront.net/foundation-A245148-d737-4f4b-9bd4-581c1c9f658a1.min.js" id="irfOuterTag"></script>
					<noscript><img src="//tl.r7ls.net/unscripted/245148" width="1" height="1"></noscript>
					<script type="text/javascript">
					var brokerID = '8388';
					var siteNumber = '0';
					var eventID = '<?php echo $eventID; ?>';
					var userinformation = '&uid=148&una=howardschwartz&uem=hschwartz@secureticketpurchase.com&ba1=&ba2=&bci=&bsa=&bco=&bpo=&bph=';
					</script>

					<div class="container" >
						<script type="text/javascript" src="https://mapwidget2.seatics.com/js?eventId=<?php echo $eventID; ?>&websiteConfigId=20732">

					</script>
						<script type="text/javascript">
						var Seatics = Seatics || {};
						Seatics.config = Seatics.config || {};
						if(document.location.origin == 'https://ticketsbroadway.com'){
							Seatics.config.checkoutUrl = "https://secure.ticketsbroadway.com/checkout/mobile2/Checkout.aspx?brokerid="+brokerID+"&sitenumber="+siteNumber+userinformation;
						}else if(document.location.origin == 'https://ticketsbroadway.net'){
							siteNumber = '7';
							Seatics.config.checkoutUrl = "https://secure.ticketsbroadway.net/checkout/mobile2/Checkout.aspx?brokerid="+brokerID+"&sitenumber="+siteNumber+userinformation;
						}


					</script>
					</div>
				</div>

			</div>


<?php get_footer(); ?>
