<?php get_header(); ?>
</head>
<body class="template template-<?= $CONTENT ?> <?= get_cookies()?>">
	<div id="page" class="wrap xl-3 xl-gutter-0">
		<div class="col xl-1-1">
			<div class="content content-<?= $CONTENT ?>">
				<?php get_content($CONTENT); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>