<?php
/**
 * Forzar el logueo y controles antes de imprimir el certificado
 */

require_once('config.php');

global $CFG, $DB, $USER;
$userid    = optional_param('userid',0, PARAM_INT);
$courseid  = optional_param('courseid',0, PARAM_INT);
$cert = optional_param('cert','', PARAM_TEXT);

//if($userid < 1 || $courseid < 1){
//    print_error('Faltan Parametros');
//}

// El usuario debe estar logueado
if (!isloggedin()) {
    require_login();
}

// El usuario logueado debe ser el mismo del parametro
if ($USER->id != $userid){
    print_error('Los usuarios no son los mismos');
    //notice('No aprobo el examen final');
}


// El usuario debe estar dentro del curso
if(!course_name($USER->id, $courseid)){
    print_error('El usuario no está matriculado en el curso/aula');    
}

if(!grades_exist($USER->id, $courseid)){
    print_error('El curso/aula no posee examen final');
}

if(!grades_necesary($USER->id, $courseid)){
    print_error('Usted no ha aprobado el examen final aún');
}



function course_name($userid,$courseid){
    global $CFG, $DB;
    
    $course_name='';
    $sql="SELECT c.fullname
        FROM {role_assignments} ra, {context} ctx, {course} c
        WHERE ra.contextid = ctx.id AND ctx.instanceid = c.id AND 
            ra.userid = ? AND ra.roleid = 5 AND ctx.contextlevel = 50 AND ctx.instanceid = ?";
    $fullnames = $DB->get_records_sql($sql, array($userid,$courseid));
    foreach ($fullnames as $fullname){
        $course_name=$fullname->fullname;
    }
        
    return $course_name;
}

function grades_exist($userid,$courseid){
    global $CFG, $DB;
    
    $maxgrade='';    
    $sql="courseid = $courseid";
    $pn_grades = $DB->get_records_select('pn_grades', $sql);
    foreach ($pn_grades as $pn_grade){
        $maxgrade = $pn_grade->grade;
    }
    
    return $maxgrade;
}


function grades_necesary($userid,$courseid){
    global $CFG, $DB;
    
    $timefinish='';
    $maxgrade='';
    $quiz='';
    
    $sql="courseid = $courseid";
    $pn_grades = $DB->get_records_select('pn_grades', $sql);
    foreach ($pn_grades as $pn_grade){
        $maxgrade = $pn_grade->grade;
        $quiz = $pn_grade->quizid;
    }
    
    $sql2="userid = $userid AND quiz = $quiz AND sumgrades >= $maxgrade";
    $quiz_attempts = $DB->get_records_select('quiz_attempts',$sql2 ,null ,'attempt ASC','timefinish',0,1);
    foreach ($quiz_attempts as $quiz_attempt){
        $timefinish = $quiz_attempt->timefinish;
    }
    
    return $timefinish;
}

function get_hs($courseid){
    global $CFG, $DB;

    $sql="course = $courseid";
    $pn_hs_courses = $DB->get_records_select('pn_hs_course', $sql);

    foreach ($pn_hs_courses as $pn_hs_course){
         $hs = $pn_hs_course->hs;
    }
        return $hs;
}

//============================================================+
// File name   : example_051.php
// Begin       : 2009-04-16
// Last Update : 2010-08-08
//
// Description : Example 051 for TCPDF class
//               Full page background
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Full page background
 * @author Nicola Asuni
 * @since 2009-04-16
 */

#require_once('lib/tcpdf/config/lang/eng.php');
require_once('lib/tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
/*
		// full background image
		// store current auto-page-break status
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		//$img_file = 'certif.jpg'; //'image_demo.jpg';
		/*var_dump($cert);
		die('rama');
		  switch($cert){
                  case 'pn':
                   $img_file='certif.jpg';
                  break;
                  case 'sya':
                   $img_file='certif_sya.jpg';
                  break;
                  case 'cei':
                   $img_file='certif_pronacei.jpg';
                  break;
                }
		$this->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
*/
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x, $y);  // SetXY($x+20, $y); // 20 = margin left
		$this->SetFont('century', $fontstyle, $fontsize); //e-ABC antes font: century
		$this->Cell($width, $height, $textval, 0, false, $align);
	}
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Programa Sumar');
$pdf->SetTitle('Certificado de Aprobación de Curso');
$pdf->SetSubject('-');
$pdf->SetKeywords('-');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('century', 'I', '36'); //e-ABC antes font century

// add a page

$nombre = ucwords(strtolower($USER->lastname)) . ', ' . ucwords(strtolower($USER->firstname));
$prov = $USER->profile['provincia'];
$curso = course_name($USER->id, $courseid);
$fecha = userdate(grades_necesary($USER->id, $courseid),'%d/%m/%Y');
//$dia= userdate(grades_necesary($USER->id, $courseid),'%d');
//$mes=userdate(grades_necesary($USER->id, $courseid),'%m');
//$anio=userdate(grades_necesary($USER->id, $courseid),'%Y');

$hs=get_hs($courseid);


	switch($cert){
                  case 'pn':
                   $img_file='theme/sumar/imagenes/fondo_certificado_sumar.jpg';
                  break;
                  case 'sya':
                   $img_file='theme/sumar/imagenes/fondo_certificados_sumar-sya.jpg';
                  break;
                  case 'cei':
                   $img_file='theme/sumar/imagenes/fondo_certificado_sumar-pronacei.jpg';
                  break;
                }
//$pdf->SetPrinterHeader(false);
$pdf->AddPage('L','A4');
$bMargin=$pdf->GetBreakMargin();


//$auto_page_break=$pdf->GetAutoPageBreak();

$pdf->SetAutoPageBreak(false,0);


$pdf->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);

$pdf->SetAutoPageBreak(TRUE,$bMargin);

//$pdf->SetPageMark();

$pdf->CreateTextBox($nombre, 15, 70, 0, 0, 34, 'B', 'C');
$pdf->CreateTextBox($prov, 15, 88, 0, 0, 20, 'B', 'C');

if (strlen($curso)<=60){
	$pdf->CreateTextBox($curso, 15, 100, 0, 0, 26, 'B', 'C');
}else{
	$curso=wordwrap($curso, 60, "|||||||||||||");
	$cursos=explode("|||||||||||||", $curso);
	$pdf->CreateTextBox($cursos[0], 15, 94, 0, 0, 21, 'B', 'C');
	$pdf->CreateTextBox($cursos[1], 15, 103, 0, 0, 21, 'B', 'C');
}

$pdf->CreateTextBox($hs, 60, 114, 0, 0, 24, 'B', 'L');
$pdf->CreateTextBox($fecha, 116, 128, 0, 0, 22, '', 'C');
//$pdf->CreateTextBox($dia, 33, 141, 0, 0, 22, '', 'C');
//$pdf->CreateTextBox($mes, 65, 141, 0, 0, 22, '', 'C');
//$pdf->CreateTextBox($anio, 102, 141, 0, 0, 22, '', 'C');

// Print a text
#$html = '<span style="background-color:yellow;color:blue;">&nbsp;PAGE 1&nbsp;</span>
#<p stroke="0.2" fill="true" strokecolor="yellow" color="blue" style="font-family:helvetica;font-weight:bold;font-size:26pt;">You can set a full page background.</p>';
#$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($USER->lastname.'.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+


// Reenvío al usuario al curso donde estaba
//redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);

?>
