<?php

function cancel_email_update($userid) {
    unset_user_preference('newemail', $userid);
    unset_user_preference('newemailkey', $userid);
    unset_user_preference('newemailattemptsleft', $userid);
}

function useredit_load_preferences(&$user, $reload=true) {
    global $USER;

    if (!empty($user->id)) {
        if ($reload and $USER->id == $user->id) {
            // reload preferences in case it was changed in other session
            unset($USER->preference);
        }

        if ($preferences = get_user_preferences(null, null, $user->id)) {
            foreach($preferences as $name=>$value) {
                $user->{'preference_'.$name} = $value;
            }
        }
    }
}

function useredit_update_user_preference($usernew) {
    $ua = (array)$usernew;
    foreach($ua as $key=>$value) {
        if (strpos($key, 'preference_') === 0) {
            $name = substr($key, strlen('preference_'));
            set_user_preference($name, $value, $usernew->id);
        }
    }
}

function useredit_update_picture(&$usernew, $userform) {
    global $CFG, $DB;
    require_once("$CFG->libdir/gdlib.php");

    $fs = get_file_storage();
    $context = get_context_instance(CONTEXT_USER, $usernew->id, MUST_EXIST);

    if (isset($usernew->deletepicture) and $usernew->deletepicture) {
        $fs->delete_area_files($context->id, 'user', 'icon'); // drop all areas
        $DB->set_field('user', 'picture', 0, array('id'=>$usernew->id));

    } else if ($iconfile = $userform->save_temp_file('imagefile')) {
        if (process_new_icon($context, 'user', 'icon', 0, $iconfile)) {
            $DB->set_field('user', 'picture', 1, array('id'=>$usernew->id));
        }
        @unlink($iconfile);
    }
}

function useredit_update_bounces($user, $usernew) {
    if (!isset($usernew->email)) {
        //locked field
        return;
    }
    if (!isset($user->email) || $user->email !== $usernew->email) {
        set_bounce_count($usernew,true);
        set_send_count($usernew,true);
    }
}

function useredit_update_trackforums($user, $usernew) {
    global $CFG;
    if (!isset($usernew->trackforums)) {
        //locked field
        return;
    }
    if ((!isset($user->trackforums) || ($usernew->trackforums != $user->trackforums)) and !$usernew->trackforums) {
        require_once($CFG->dirroot.'/mod/forum/lib.php');
        forum_tp_delete_read_records($usernew->id);
    }
}

function useredit_update_interests($user, $interests) {
    tag_set('user', $user->id, $interests);
}

function useredit_shared_definition(&$mform, $editoroptions = null) {
    global $CFG, $USER, $DB;

    $user = $DB->get_record('user', array('id' => $USER->id));
    useredit_load_preferences($user, false);

    $strrequired = get_string('required');

    $nameordercheck = new stdClass();
    $nameordercheck->firstname = 'a';
    $nameordercheck->lastname  = 'b';
    if (fullname($nameordercheck) == 'b a' ) {  // See MDL-4325
        $mform->addElement('text', 'lastname',  get_string('lastname'),  'maxlength="100" size="30"');
        $mform->addElement('text', 'firstname', get_string('firstname'), 'maxlength="100" size="30"');
    } else {
        $mform->addElement('text', 'firstname', get_string('firstname'), 'maxlength="100" size="30"');
        $mform->addElement('text', 'lastname',  get_string('lastname'),  'maxlength="100" size="30"');
    }

    $mform->addRule('firstname', $strrequired, 'required', null, 'client');
    $mform->setType('firstname', PARAM_NOTAGS);

    $mform->addRule('lastname', $strrequired, 'required', null, 'client');
    $mform->setType('lastname', PARAM_NOTAGS);


    /////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////




    // Do not show email field if change confirmation is pending
    if (!empty($CFG->emailchangeconfirmation) and !empty($user->preference_newemail)) {
        $notice = get_string('emailchangepending', 'auth', $user);
        $notice .= '<br /><a href="edit.php?cancelemailchange=1&amp;id='.$user->id.'">'
                . get_string('emailchangecancel', 'auth') . '</a>';
        $mform->addElement('static', 'emailpending', get_string('email'), $notice);
    } else {
        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="30"');
        $mform->addRule('email', $strrequired, 'required', null, 'client');
    }

   //////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    
    $mform->addElement('hidden', 'country',  'AR');
    $mform->addElement('static', 'pais', get_string('country'), 'Argentina');

//    $choices = get_string_manager()->get_list_of_countries();
//    $choices= array(''=>get_string('selectacountry').'...') + $choices;
//    $mform->addElement('select', 'country', get_string('selectacountry'), $choices);
//    $mform->addRule('country', $strrequired, 'required', null, 'client');
//    if (!empty($CFG->country)) {
//        $mform->setDefault('country', $CFG->country);
//    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $mform->addElement('hidden', 'lang', get_string('preferredlanguage'), get_string_manager()->get_list_of_translations());
//    $mform->setDefault('lang', $CFG->lang);


    $mform->addElement('editor', 'description_editor', get_string('userdescription'), null, $editoroptions);
    $mform->setType('description_editor', PARAM_CLEANHTML);
    $mform->addHelpButton('description_editor', 'userdescription');


    if (!empty($CFG->gdversion)) {
//      $mform->addElement('header', 'moodle_picture', get_string('pictureofuser'));
        $mform->addElement('header', '', 'Usar Imagen');

        $mform->addElement('static', 'currentpicture', get_string('currentpicture'));

        $mform->addElement('checkbox', 'deletepicture', get_string('delete'));
        $mform->setDefault('deletepicture', 0);

        $mform->addElement('filepicker', 'imagefile', get_string('newpicture'), '', array('maxbytes'=>get_max_upload_file_size($CFG->maxbytes)));
        $mform->addHelpButton('imagefile', 'newpicture');

        $mform->addElement('text', 'imagealt', get_string('imagealt'), 'maxlength="100" size="30"');
        $mform->setType('imagealt', PARAM_MULTILANG);

    }

    


    
    if (!empty($CFG->usetags)) {
        $mform->addElement('header', 'moodle_interests', get_string('interests'));
        $mform->addElement('tags', 'interests', get_string('interestslist'), array('display' => 'noofficial'));
        $mform->addHelpButton('interests', 'interestslist');
    }

    /// Moodle optional fields
    $mform->addElement('header', 'moodle_optional', get_string('optional', 'form'));

    $mform->addElement('text', 'url', get_string('webpage'), 'maxlength="255" size="50"');
    $mform->setType('url', PARAM_URL);

    $mform->addElement('text', 'icq', get_string('icqnumber'), 'maxlength="15" size="25"');
    $mform->setType('icq', PARAM_NOTAGS);

    $mform->addElement('text', 'skype', get_string('skypeid'), 'maxlength="50" size="25"');
    $mform->setType('skype', PARAM_NOTAGS);

    $mform->addElement('text', 'aim', get_string('aimid'), 'maxlength="50" size="25"');
    $mform->setType('aim', PARAM_NOTAGS);

    $mform->addElement('text', 'yahoo', get_string('yahooid'), 'maxlength="50" size="25"');
    $mform->setType('yahoo', PARAM_NOTAGS);

    $mform->addElement('text', 'msn', get_string('msnid'), 'maxlength="50" size="25"');
    $mform->setType('msn', PARAM_NOTAGS);

    $mform->addElement('text', 'idnumber', get_string('idnumber'), 'maxlength="255" size="25"');
    $mform->setType('idnumber', PARAM_NOTAGS);

    $mform->addElement('text', 'institution', get_string('institution'), 'maxlength="40" size="25"');
    $mform->setType('institution', PARAM_MULTILANG);

}


function personalizados(&$mform, $editoroptions = null){
	global $DB, $CFG;



    $mform->addElement('header', '', 'DATOS PARA PLAN NACER/SUMAR','');
    
	add_field_to_form('TDNI',$mform);
    add_field_to_form('NDNI',$mform);
    $mform->setType('profile_field_NDNI', PARAM_INT);
    $mform->addRule('profile_field_NDNI', 'Ingresar solo números', 'numeric', null, 'client');
    $mform->addRule('profile_field_NDNI', 'Ingresar el número de documento', 'required', null, 'client');

    add_field_to_form('edad',$mform);

    add_field_to_form('sexo',$mform);
    $mform->addRule('profile_field_sexo', 'Indique un sexo', 'required', null, 'client');

    $mform->addElement('text', 'phone1', get_string('phone'), 'maxlength="20" size="25"');
    $mform->setType('phone1', PARAM_NOTAGS);
    $mform->addRule('phone1', 'Escribir: teléfono de contacto', 'required', null, 'client');


     add_field_to_form('nivel',$mform);
    $mform->addRule('profile_field_nivel', 'Indique el nivel educativo', 'required', null, 'client');

    add_field_to_form('provincia',$mform);
    $mform->addRule('profile_field_provincia', 'Seleccione una provincia', 'required', null, 'client');
    
    $mform->addElement('text', 'department', 'Partido / Depto.', 'maxlength="30" size="25"');
    $mform->setType('department', PARAM_TEXT);
    $mform->addRule('department', 'Escribir: Partido / Depto.', 'required', null, 'client');

    $mform->addElement('text','city' ,'Ciudad / Pueblo', 'maxlength="120" size="20"');
    $mform->setType('city', PARAM_TEXT);
    $mform->addRule('city', 'Escribir: Ciudad / Pueblo', 'required', null, 'client');



    

    $mform->addElement('header', '', 'Datos laborales','');
    $mform->addElement('hidden', 'profile_field_efector',  '');

    add_field_to_form('trabaja', $mform);
    $mform->addRule('profile_field_trabaja', 'Indique en donde trabaja', 'required', null, 'client');

    $mform->addElement('select', 'profile_field_tconvenio', 'Tipo de Convenio', select_options_to_form('tconvenio'));
    $mform->disabledIf('profile_field_tconvenio', 'profile_field_trabaja','noteq',0);
    
    $provincia = array();
    $provincia[''] = get_string('choose').'...';
    if ($fields = $DB->get_records('pn_provincia')) {
        foreach ($fields as $field) {
            $provincia[$field->id] = $field->nombre;
        }
    }
    
    $efectores = array();
    $efectores['']['']=get_string('choose').'...';
    if ($fields = $DB->get_records('pn_efectores',array(),'id_provincia ASC, nombre ASC')) {
       $id=0;
        foreach ($fields as $field) {
            if ($id != $field->id_provincia){
                $efectores[$field->id_provincia][''] = get_string('choose').'...';
                $id = $field->id_provincia;
            }
            $efectores[$field->id_provincia][$field->cuie] = $field->nombre;
        }
    }

    //$default_provincia[''] = get_string('choose').'...';
    //$default_efectores[''] = get_string('choose').'...';        

    //$provincia = array_merge($default_provincia, $provincia);
    //$efectores = array_merge($default_efectores, $efectores);

    $mform->addElement('hierselect', 'efectores', 'Establecimientos');
    $mform->getElement('efectores')->setOptions(array($provincia, $efectores));

    if($CFG->profile_field_efector != ''){
        $pn_efector = $DB->get_records('pn_efectores',array('cuie'=>$CFG->profile_field_efector));
        $select_provincia=$pn_efector[$CFG->profile_field_efector]->id_provincia;
        //$select_provincia=$pn_efector[$CFG->profile_field_efector]->id_provincia - 1;
        $select_efector=$pn_efector[$CFG->profile_field_efector]->cuie;
        $mform->getElement('efectores')->setValue(array($select_provincia, $select_efector));
    }
    $mform->disabledIf('efectores', 'profile_field_trabaja','noteq',0);
    $mform->disabledIf('efectores', 'profile_field_tconvenio','noteq',0);


    add_field_to_form('estOtro', $mform);
    $mform->disabledIf('profile_field_estOtro', 'profile_field_trabaja','noteq',0);
    $mform->disabledIf('profile_field_estOtro', 'profile_field_tconvenio','noteq',1);


    $mform->addElement('select', 'profile_field_orgGubernamental', 'Organismo', select_options_to_form('orgGubernamental'));
    $mform->disabledIf('profile_field_orgGubernamental', 'profile_field_trabaja','noteq',1);
    add_field_to_form('orgOtro', $mform);
    $mform->disabledIf('profile_field_orgOtro', 'profile_field_trabaja','noteq',1);
    $mform->disabledIf('profile_field_orgOtro', 'profile_field_orgGubernamental','eq','');


    $mform->addElement('header', '', 'Datos de sus funciones','');

    add_field_to_form('cargo',$mform);
    $mform->addRule('profile_field_cargo', 'Indique que función desempeña', 'required', null, 'client');

    $mform->addElement('select', 'profile_field_listaprof', 'Lista de Profesionales', select_options_to_form('listaprof'));
    $mform->disabledIf('profile_field_listaprof', 'profile_field_cargo','noteq',1);

    add_field_to_form('cargoOtro',$mform);
    $mform->disabledIf('profile_field_cargoOtro', 'profile_field_cargo','eq',1);
    $mform->disabledIf('profile_field_cargoOtro', 'profile_field_cargo','eq','');
}


    /**
     * Adds code snippet to a moodle form object for custom profile fields that
     * should appear on the signup page
     * @param string Add field name
     */
    function add_field_to_form($field,&$mform){
        global $CFG, $DB;
        //only retrieve required custom fields (with category information)
        //results are sort by categories, then by fields
        $sql = "SELECT uf.id as fieldid, ic.id as categoryid, ic.name as categoryname, uf.datatype, uf.shortname
                    FROM {user_info_field} uf
                    JOIN {user_info_category} ic
                    ON uf.categoryid = ic.id AND uf.signup = 1 AND uf.visible<>0
                    WHERE uf.shortname = '$field'
                    ORDER BY ic.sortorder ASC, uf.sortorder ASC";

        if ( $fields = $DB->get_records_sql($sql)) {
            foreach ($fields as $field) {
                require_once($CFG->dirroot.'/user/profile/field/'.$field->datatype.'/field.class.php');
                $newfield = 'profile_field_'.$field->datatype;
                $formfield = new $newfield($field->fieldid);
                $formfield->edit_field($mform);

            }
        }
    }

     /**
     * Adds code snippet to a moodle form object for custom profile fields that
     * should appear on the signup page
     * @param string Add field shortname
     * @return array Options to select
     */
    function select_options_to_form($shortname){
        global $CFG, $DB;
        $param1 = $DB->get_record('user_info_field', array('shortname'=>$shortname));
        $default_options[''] = get_string('choose').'...';
        $options = explode("\n", $param1->param1);
        $options = array_merge($default_options, $options);
        return $options;
    }
