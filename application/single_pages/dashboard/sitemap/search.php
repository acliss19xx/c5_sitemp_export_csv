<?php defined('C5_EXECUTE') or die('Access Denied'); ?>
<?php
$dh = Loader::helper('concrete/dashboard/sitemap');
if ($dh->canRead()) {
    ?>

	<div class="ccm-dashboard-content-full">
		<form method="post" name="cIDsForm" action="<?php echo $view->action('csv')?>">
			<?php Core::make('token')->output('sitemap-csv'); ?>
			<a href="javascript:cIDsForm.submit()" class="btn btn-success">
				<i class="fa fa-download"></i><?php echo t('Export to CSV') ?>
			</a>
		</form>
		<?php Loader::element('pages/search', array('result' => $result))?>
	</div>
<?php
} else {
    ?>
	<p><?=t("You must have access to the dashboard sitemap to search pages.")?></p>
<?php
} ?>
