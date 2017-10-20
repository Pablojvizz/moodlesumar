<?php
class block_soporteeabc extends block_base {
    public function init() {
        $this->title = get_string('soporteeabc', 'block_soporteeabc');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
    global $DB;
    global $CFG;
    global $USER;
    if ($this->content !== null) {
      return $this->content;
    }
    $sql = 'select distinct contenthash , filesize from {files}';
    $files = $DB->get_records_sql($sql,null,0,0);
    $peso = 0;
    
    foreach ($files as $file){
        $peso = $peso + $file->filesize;
    }
    
    if ($peso >= 1000 && $peso < 1000000){
        $peso = round($peso / 1000 , 1) . 'B';
    }
    if ( $peso >= 1000000 && $peso < 1000000000){
        $peso = round($peso / 1000000 , 1) . 'MB';
    }
    if ($peso >= 1000000000){
        $peso = round($peso / 1000000000 , 1) . 'GB';
    }
    $site = $DB->get_record('course',array('id'=>1));
    $plataforma = $site->shortname;
    $this->content        =  new stdClass;
    $this->content->text  = '<center><p>'.get_string('totalsize', 'block_soporteeabc').'</p><p style="text-align:center;font-size:20px">';
    $this->content->text .=  $peso; 
    $this->content->text .= '</p><hr /><br />';

    $fullname = $USER->firstname .' '. $USER->lastname;
    $email = $USER->email;
    $url = urlencode($CFG->wwwroot);
    $this->content->text .= '<p><a id="IMG_Soporte_e-ABC" href="http://soporte.e-abclearning.com/open.php?cliente='.$plataforma.' - '.$fullname.'&email='.$email.'&url='.$url.'" target="_blank"><img alt="Soporte e-ABC" src="blocks/soporteeabc/img/new_ticket_title.jpg" height="50" width="160" /></a></p><p>'.get_string('support', 'block_soporteeabc').'</p></center>';
    return $this->content;
  }
}   // Here's the closing bracket for the class definition



?>