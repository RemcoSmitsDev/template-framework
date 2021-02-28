<?php get_header(); ?>
</head>
<body class="template template-<?= Content::get(); ?> <?= get_cookies()?>">
	<div id="page" class="wrap xl-3 xl-gutter-0">
		<div class="col xl-1-1">
			<div class="content content-<?= Content::get(); ?>">
				<?php Content::view(); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
