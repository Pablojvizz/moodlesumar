<?php

$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$showsidepre = $hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT);
$showsidepost = $hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT);

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <!--<link rel="stylesheet" href="<?php echo $CFG->wwwroot?>/contact_form/style.css" type = "text/css"/>
	<script type="text/javascript" src="<?php echo $CFG->wwwroot?>/contact_form/js/jq/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $CFG->wwwroot?>/contact_form/js/contact.js"></script>-->
    <meta name="description" content="<?php p(strip_tags(format_text($SITE->summary, FORMAT_HTML))) ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">

    <div id="page-header" class="clearfix">
        <h1 class="headermain"><?php echo $PAGE->heading ?></h1>
        <div class="headermenu"><?php
            // echo $OUTPUT->login_info(); // 
            echo $OUTPUT->lang_menu();
            echo $PAGE->headingmenu;
        ?></div>
        <?php if ($hascustommenu) { ?>
        <div id="custommenu"><?php echo $custommenu; ?></div>
         <?php } ?>
          <?php if ($hasnavbar) { ?>
            <div class="navbar clearfix">
                <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                <div class="navbutton"> <?php echo $PAGE->button; ?></div>
            </div>
        <?php } ?>
    </div>
<!-- END OF HEADER -->

    <div id="page-content">
        <div id="region-main-box">
            <div id="region-post-box">

                <div id="region-main-wrap">
                    <div id="region-main">
                        <div class="region-content">
						<!--<a class="" href="javascript:verFormulario()">Ver Formulario</a>
						<div id = "contact_form" class="">
							<p>Formulario de contacto</p>
							
							<form action="<?php $CFG->wwwroot ?>/contact_form/contact.php" method="post"> 
								<fieldset>
								<div>
								<label for="nombre">Nombre y apellidos : </label> 
								<input type="text" name="nombre" size="35" maxlength="80" id = "nombre">
								</div>
								<div>
								<label for="email">Email : </label>
								<input type="text" name="email" size="35" maxlength="60" id = "email">
								</div>
								<div>
								<label for="asunto">Asunto : </label>
								<input type="text" name="asunto" size="35" maxlength="60" id = "asunto">  
								</div>
								<div>
								<label for="mensaje">Mensaje : </label>
								<textarea name="mensaje" cols="31" rows="5" id = "mensaje"></textarea>
								</div>
								<div>
								<input type="submit" name="enviar" value="Enviar consulta" href="javascript:;" onclick="enviarMail($('#nombre').val(), $('#email').val(),$('#asunto').val(),$('#mensaje').val());return false;"></label> <br/>
								<input type="button" name="cerrar" value="Cerrar el formulario" onClick="cerrarFormulario()"></label>
								</div>
								</fieldset>
							</form>
							Resultado: <span id="resultado">0</span>
						</div>-->
                            <?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
                        </div>
                    </div>
                </div>

                <?php if ($hassidepre) { ?>
                <div id="region-pre" class="block-region">
                    <div class="region-content">
                        <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
                    </div>
                </div>
                <?php } ?>

                <?php if ($hassidepost) { ?>
                <div id="region-post" class="block-region">
                    <div class="region-content">
                        <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

<!-- START OF FOOTER -->
    <div id="page-footer" class="clearfix">
         <div class="footermenu">
              <?php echo $OUTPUT->login_info(); ?>
         </div>
         <div class="credits">
                       <a href="http://www.e-abclearning.com" target="_blank"><img src="/theme/sumar/pix/logo_eabc.png" border="0" alt="e-ABC" /></a>&nbsp;
            <a href="http://moodle.org" target="_blank"><img src="/theme/sumar/pix/logo_moodle.png" border="0" alt="Moodle"/></a>&nbsp;&nbsp;
        </div>
    </div>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>